<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Anggota;
use App\Models\Wisata;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_displays_the_admin_form(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('IPNU Admin')
            ->assertSee('name="username"', false)
            ->assertSee('name="password"', false);
    }

    public function test_public_homepage_renders_animated_welcome_content(): void
    {
        $this->get(route('beranda'))
            ->assertOk()
            ->assertSee('home-hero', false)
            ->assertSee('hero-primary', false)
            ->assertSee('Kenali program infaq')
            ->assertSee(route('infaq'))
            ->assertDontSee('Panel Kontrol Admin');
    }

    public function test_dashboard_redirects_to_login_when_not_authenticated(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_invalid_credentials_return_an_error_to_login(): void
    {
        config([
            'admin.username' => 'admin',
            'admin.password_hash' => Hash::make('test-admin-password'),
        ]);

        $this->from(route('login'))
            ->post(route('login.process'), [
                'username' => 'admin',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('username');
    }

    public function test_valid_credentials_start_admin_session_and_redirect_to_dashboard(): void
    {
        config([
            'admin.username' => 'admin',
            'admin.password_hash' => Hash::make('test-admin-password'),
        ]);

        $this->post(route('login.process'), [
            'username' => 'admin',
            'password' => 'test-admin-password',
        ])
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('admin_authenticated', true);
    }

    public function test_login_fails_closed_when_admin_credentials_are_not_configured(): void
    {
        config(['admin.username' => null, 'admin.password_hash' => null]);

        $this->from(route('login'))
            ->post(route('login.process'), [
                'username' => 'admin',
                'password' => 'any-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('username')
            ->assertSessionMissing('admin_authenticated');
    }

    public function test_authenticated_admin_can_open_the_dashboard(): void
    {
        $this->withSession(['admin_authenticated' => true])
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Grafik Statistik Portal');
    }

    public function test_admin_module_pages_and_print_previews_render(): void
    {
        $this->withSession(['admin_authenticated' => true]);

        foreach ([
            'admin.anggota.index',
            'admin.agenda.index',
            'admin.wisata.index',
            'admin.saran.index',
            'admin.cetak.index',
        ] as $routeName) {
            $this->get(route($routeName))->assertOk();
        }

        $this->get(route('admin.anggota.index'))
            ->assertOk()
            ->assertSee('Cetak Anggota');
        $this->post(route('admin.cetak.preview'), ['type' => 'anggota'])->assertOk();
        $this->post(route('admin.cetak.preview'), ['type' => 'agenda'])->assertOk();
    }

    public function test_admin_can_create_members_agendas_and_destinations(): void
    {
        $this->withSession(['admin_authenticated' => true]);

        $this->post(route('admin.anggota.store'), [
            'nama' => 'Anggota Tes',
            'jabatan' => 'Ketua',
            'divisi' => 'Organisasi',
            'periode' => '2026-2027',
        ])->assertSessionHasNoErrors();

        $this->post(route('admin.agenda.store'), [
            'judul' => 'Agenda Tes',
            'tanggal' => '2026-10-10',
            'waktu' => '08.00 WIB',
            'lokasi' => 'Balai Desa',
            'deskripsi' => 'Kegiatan pengujian.',
            'status' => 'Akan Datang',
        ])->assertSessionHasNoErrors();

        $this->post(route('admin.wisata.store'), [
            'nama' => 'Wisata Tes',
            'lokasi' => 'Karangpundung',
            'deskripsi' => 'Destinasi pengujian.',
            'fasilitas' => 'Area parkir',
            'jam_buka' => '08.00-17.00 WIB',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('anggotas', ['nama' => 'Anggota Tes']);
        $this->assertDatabaseHas('agendas', ['judul' => 'Agenda Tes']);
        $this->assertDatabaseHas('wisatas', ['nama' => 'Wisata Tes']);
    }

    public function test_member_print_preview_includes_the_uploaded_photo(): void
    {
        $this->withSession(['admin_authenticated' => true]);

        Anggota::create([
            'nama' => 'Anggota Foto',
            'jabatan' => 'Ketua',
            'divisi' => 'Organisasi',
            'periode' => '2026-2027',
            'foto' => 'anggota/contoh.jpg',
        ]);

        $this->post(route('admin.cetak.preview'), ['type' => 'anggota'])
            ->assertOk()
            ->assertSee('NU KARANGPUNDUNG')
            ->assertSee('member-card')
            ->assertSee('storage/anggota/contoh.jpg')
            ->assertDontSee('Panel Kontrol Admin');
    }

    public function test_admin_member_and_tourism_tables_show_uploaded_photos(): void
    {
        $this->withSession(['admin_authenticated' => true]);

        Anggota::create([
            'nama' => 'Anggota Foto',
            'jabatan' => 'Ketua',
            'divisi' => 'Organisasi',
            'periode' => '2026-2027',
            'foto' => 'anggota/anggota.jpg',
        ]);
        Wisata::create([
            'nama' => 'Wisata Foto',
            'lokasi' => 'Karangpundung',
            'deskripsi' => 'Destinasi dengan foto.',
            'fasilitas' => 'Area parkir',
            'jam_buka' => '08.00-17.00 WIB',
            'foto' => 'wisata/wisata.jpg',
        ]);

        $this->get(route('admin.anggota.index'))
            ->assertOk()
            ->assertSee('storage/anggota/anggota.jpg');
        $this->get(route('admin.wisata.index'))
            ->assertOk()
            ->assertSee('storage/wisata/wisata.jpg');
    }

    public function test_authenticated_admin_is_redirected_away_from_login(): void
    {
        $this->withSession(['admin_authenticated' => true])
            ->get(route('login'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_logout_clears_the_admin_session(): void
    {
        $this->withSession(['admin_authenticated' => true])
            ->post(route('logout'))
            ->assertRedirect(route('login'))
            ->assertSessionMissing('admin_authenticated');
    }
}