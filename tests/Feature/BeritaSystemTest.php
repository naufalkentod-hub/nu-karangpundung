<?php

namespace Tests\Feature;

use App\Models\Berita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BeritaSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_search_filter_and_read_published_news_only(): void
    {
        Berita::create([
            'judul' => 'Kegiatan Bakti Sosial',
            'slug' => 'kegiatan-bakti-sosial',
            'kategori' => 'Kegiatan',
            'ringkasan' => 'Pemuda NU mengadakan kegiatan sosial.',
            'isi' => 'Isi berita bakti sosial.',
            'tanggal_publikasi' => today()->subDay(),
        ]);
        Berita::create([
            'judul' => 'Berita Terjadwal Rahasia',
            'slug' => 'berita-terjadwal-rahasia',
            'kategori' => 'Kegiatan',
            'ringkasan' => 'Belum boleh dibaca publik.',
            'isi' => 'Isi terjadwal.',
            'tanggal_publikasi' => today()->addDay(),
        ]);

        $this->get(route('berita.index'))
            ->assertOk()
            ->assertSee('Berita')
            ->assertSee('href="'.route('berita.index').'"', false)
            ->assertSee('Kegiatan Bakti Sosial')
            ->assertDontSee('Berita Terjadwal Rahasia');

        $this->get(route('berita.index', ['search' => 'bakti', 'kategori' => 'Kegiatan']))
            ->assertOk()
            ->assertSee('Kegiatan Bakti Sosial')
            ->assertDontSee('Berita Terjadwal Rahasia');

        $this->get(route('berita.show', 'kegiatan-bakti-sosial'))
            ->assertOk()
            ->assertSee('Isi berita bakti sosial.');
        $this->get(route('berita.show', 'berita-terjadwal-rahasia'))->assertNotFound();
    }

    public function test_admin_only_can_manage_news_and_can_create_update_and_delete_it(): void
    {
        Storage::fake('public');

        $this->get(route('admin.berita.index'))->assertRedirect(route('login'));
        $this->post(route('admin.berita.store'), [])->assertRedirect(route('login'));

        $this->withSession(['admin_authenticated' => true])
            ->get(route('admin.berita.index'))
            ->assertOk()
            ->assertSee('Tambah Berita');

        $this->withSession(['admin_authenticated' => true])
            ->post(route('admin.berita.store'), [
                'judul' => 'Berita Pengurus',
                'kategori' => 'Kegiatan',
                'ringkasan' => 'Ringkasan berita pengurus.',
                'isi' => 'Isi berita pengurus.',
                'tanggal_publikasi' => today()->format('Y-m-d'),
                'gambar' => UploadedFile::fake()->create('berita.jpg', 100, 'image/jpeg'),
            ])
            ->assertRedirect(route('admin.berita.index'))
            ->assertSessionHasNoErrors();

        $berita = Berita::firstOrFail();
        $this->assertSame('berita-pengurus', $berita->slug);
        Storage::disk('public')->assertExists($berita->gambar);

        $this->withSession(['admin_authenticated' => true])
            ->put(route('admin.berita.update', $berita), [
                'judul' => 'Berita Pengurus Terbaru',
                'kategori' => 'Organisasi',
                'ringkasan' => 'Ringkasan diperbarui.',
                'isi' => 'Isi berita diperbarui.',
                'tanggal_publikasi' => today()->format('Y-m-d'),
            ])
            ->assertRedirect(route('admin.berita.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('beritas', [
            'id' => $berita->id,
            'slug' => 'berita-pengurus-terbaru',
            'kategori' => 'Organisasi',
            'gambar' => $berita->gambar,
        ]);
        Storage::disk('public')->assertExists($berita->gambar);

        $this->withSession(['admin_authenticated' => true])
            ->delete(route('admin.berita.destroy', $berita))
            ->assertRedirect(route('admin.berita.index'));
        $this->assertDatabaseMissing('beritas', ['id' => $berita->id]);
        Storage::disk('public')->assertMissing($berita->gambar);
    }

    public function test_generated_slugs_are_unique(): void
    {
        $this->withSession(['admin_authenticated' => true]);
        $data = [
            'judul' => 'Kabar NU',
            'kategori' => 'Organisasi',
            'ringkasan' => 'Ringkasan kabar.',
            'isi' => 'Isi kabar.',
            'tanggal_publikasi' => today()->format('Y-m-d'),
        ];

        $this->post(route('admin.berita.store'), $data)->assertSessionHasNoErrors();
        $this->post(route('admin.berita.store'), $data)->assertSessionHasNoErrors();

        $this->assertDatabaseHas('beritas', ['slug' => 'kabar-nu']);
        $this->assertDatabaseHas('beritas', ['slug' => 'kabar-nu-2']);
    }
}
