<?php

namespace Tests\Feature;

use App\Models\Infaq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class InfaqSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_counts_only_verified_payments_and_hides_donor_identity(): void
    {
        $this->makeInfaq([
            'donor_name' => 'Nama Rahasia',
            'amount' => 50000,
            'payment_status' => Infaq::STATUS_PAID,
            'paid_at' => now(),
        ]);
        $this->makeInfaq([
            'donor_name' => 'Donatur Menunggu',
            'amount' => 25000,
            'payment_status' => Infaq::STATUS_PENDING,
        ]);
        $this->makeInfaq([
            'donor_name' => 'Donatur Perlu Verifikasi',
            'amount' => 40000,
            'payment_status' => Infaq::STATUS_PENDING_VERIFICATION,
            'manual_confirmation_at' => now(),
        ]);
        $this->makeInfaq([
            'amount' => 10000,
            'payment_status' => Infaq::STATUS_REJECTED,
        ]);

        $this->get(route('infaq'))
            ->assertOk()
            ->assertSee('Rp50.000')
            ->assertSee('1 transaksi')
            ->assertSee('Donatur')
            ->assertDontSee('Nama Rahasia')
            ->assertDontSee('Donatur Menunggu')
            ->assertDontSee('Donatur Perlu Verifikasi');
    }

    public function test_donation_is_created_pending_and_duplicate_submission_is_idempotent(): void
    {
        $this->configureOfficialBank();
        $key = (string) Str::uuid();
        $payload = [
            'idempotency_key' => $key,
            'donor_name' => 'Nama Donatur',
            'is_anonymous' => '1',
            'amount' => '25000',
            'payment_method' => 'manual_transfer',
            'consent' => '1',
        ];

        $response = $this->post(route('infaq.store'), $payload);
        $infaq = Infaq::firstOrFail();

        $response->assertRedirect(route('infaq.transaction', $infaq->public_token));
        $this->assertSame(Infaq::STATUS_PENDING, $infaq->payment_status);
        $this->assertTrue($infaq->is_anonymous);
        $this->assertNull($infaq->donor_name);
        $this->get(route('infaq.transaction', $infaq->public_token))
            ->assertOk()
            ->assertSee('Bank Resmi')
            ->assertSee('123456789')
            ->assertSee('Rp25.000')
            ->assertSee($infaq->transaction_code)
            ->assertSee('Saya sudah transfer')
            ->assertSee('name="proof"', false);

        $this->post(route('infaq.store'), $payload)
            ->assertRedirect(route('infaq.transaction', $infaq->public_token));
        $this->assertDatabaseCount('infaqs', 1);
    }

    public function test_form_and_pending_request_work_when_official_bank_details_are_missing(): void
    {
        config([
            'infaq.bank_name' => null,
            'infaq.account_number' => null,
            'infaq.account_holder' => null,
        ]);

        $this->get(route('infaq.create'))
            ->assertOk()
            ->assertSee('Instruksi pembayaran belum tersedia')
            ->assertSee('name="amount"', false)
            ->assertSee('name="payment_method"', false)
            ->assertSee('Kirim Permohonan Infaq')
            ->assertSee('Nominal lainnya');

        $response = $this->post(route('infaq.store'), [
            'idempotency_key' => (string) Str::uuid(),
            'donor_name' => 'Donatur',
            'amount' => 25000,
            'payment_method' => 'manual_transfer',
            'consent' => '1',
        ]);

        $infaq = Infaq::firstOrFail();
        $response->assertRedirect(route('infaq.transaction', $infaq->public_token));
        $this->assertSame(Infaq::STATUS_PENDING, $infaq->payment_status);
        $this->assertSame(25000, $infaq->amount);
        $this->assertNull($infaq->paid_at);

        $this->withSession(['admin_authenticated' => true])
            ->get(route('admin.infaq.index'))
            ->assertOk()
            ->assertSee($infaq->transaction_code)
            ->assertSee('Rp25.000')
            ->assertSee('Menunggu instruksi / transfer');

        $this->get(route('infaq.transaction', $infaq->public_token))
            ->assertOk()
            ->assertSee('menunggu pengurus mengonfigurasi rekening resmi')
            ->assertDontSee('123456789');
    }

    public function test_transaction_page_uses_a_private_token_and_does_not_expose_donor_details(): void
    {
        $infaq = $this->makeInfaq([
            'donor_name' => 'Nama Pribadi',
            'message' => 'Pesan yang hanya untuk admin',
        ]);

        $this->get(route('infaq.transaction', $infaq->public_token))
            ->assertOk()
            ->assertHeader('referrer-policy', 'no-referrer')
            ->assertHeader('cache-control', 'no-store, private')
            ->assertSee($infaq->transaction_code)
            ->assertSee('Instruksi pembayaran belum tersedia')
            ->assertDontSee('Nama Pribadi')
            ->assertDontSee('Pesan yang hanya untuk admin');
    }

    public function test_manual_confirmation_is_unavailable_until_official_bank_details_are_configured(): void
    {
        $infaq = $this->makeInfaq();

        $this->post(route('infaq.confirm', $infaq->public_token))
            ->assertSessionHas('status_notice');
        $this->assertNull($infaq->fresh()->manual_confirmation_at);
    }

    public function test_server_rejects_non_positive_fractional_and_excessive_amounts(): void
    {
        $this->configureOfficialBank();

        foreach (['0', '-100', '100.50', '1000000001'] as $amount) {
            $this->post(route('infaq.store'), [
                'idempotency_key' => (string) Str::uuid(),
                'donor_name' => 'Donatur',
                'amount' => $amount,
                'payment_method' => 'manual_transfer',
                'consent' => '1',
            ])->assertSessionHasErrors('amount');
        }

        $this->assertDatabaseCount('infaqs', 0);
    }

    public function test_only_admin_can_access_infaq_management(): void
    {
        $this->get(route('admin.infaq.index'))->assertRedirect(route('login'));

        $infaq = $this->makeInfaq();
        $this->patch(route('admin.infaq.verify', $infaq), [
            'confirmed_received' => '1',
            'verification_reference' => 'Mutasi 01',
        ])->assertRedirect(route('login'));
    }

    public function test_donor_confirmation_is_idempotent_and_admin_can_verify_after_it(): void
    {
        config(['admin.username' => 'admin']);
        $this->configureOfficialBank();
        Storage::fake('infaq_private');
        $infaq = $this->makeInfaq();

        $this->withSession(['admin_authenticated' => true])
            ->get(route('admin.infaq.show', $infaq))
            ->assertOk()
            ->assertSee('Verifikasi sebagai berhasil')
            ->assertDontSee('disabled', false)
            ->assertDontSee('Menunggu konfirmasi dan bukti transfer dari donor sebelum dapat diverifikasi');

        $confirmUrl = route('infaq.confirm', $infaq->public_token);
        $this->post($confirmUrl, [
            'proof' => $this->fakePng('receipt.png'),
            'amount' => 1,
            'payment_status' => Infaq::STATUS_PAID,
        ])
            ->assertSessionHas('success');
        $infaq->refresh();
        $this->assertSame(Infaq::STATUS_PENDING_VERIFICATION, $infaq->payment_status);
        $this->assertSame(25000, $infaq->amount);
        $confirmedAt = $infaq->manual_confirmation_at;
        $this->assertNotNull($confirmedAt);
        Storage::disk('infaq_private')->assertExists($infaq->proof_path);

        $this->post($confirmUrl, ['proof' => $this->fakePng('duplicate.png')])
            ->assertSessionHas('status_notice');
        $this->assertEquals($confirmedAt, $infaq->fresh()->manual_confirmation_at);
        $this->assertSame(1, Infaq::where('payment_status', Infaq::STATUS_PENDING_VERIFICATION)->count());

        $this->withSession(['admin_authenticated' => true])
            ->get(route('admin.infaq.index'))
            ->assertOk()
            ->assertSee($infaq->transaction_code)
            ->assertSee('Perlu verifikasi');
        $this->withSession(['admin_authenticated' => true])
            ->get(route('admin.infaq.proof', $infaq))
            ->assertOk()
            ->assertHeader('cache-control', 'no-store, private');
        Storage::disk('local')->assertMissing($infaq->proof_path);

        $this->withSession(['admin_authenticated' => true])
            ->patch(route('admin.infaq.verify', $infaq), [
                'confirmed_received' => '1',
                'verification_reference' => 'Mutasi resmi 01',
            ])
            ->assertSessionHas('success');

        $infaq->refresh();
        $this->assertSame(Infaq::STATUS_PAID, $infaq->payment_status);
        $this->assertNotNull($infaq->paid_at);
        $this->assertSame('admin', $infaq->reviewed_by);
        $this->assertSame(25000, (int) Infaq::where('payment_status', Infaq::STATUS_PAID)->sum('amount'));

        $this->withSession(['admin_authenticated' => true])
            ->patch(route('admin.infaq.verify', $infaq), [
                'confirmed_received' => '1',
                'verification_reference' => 'Mutasi resmi 01',
            ])
            ->assertSessionHasErrors('payment');
        $this->assertSame(1, Infaq::where('payment_status', Infaq::STATUS_PAID)->count());
    }

    public function test_admin_can_manually_verify_pending_payment_without_donor_confirmation_or_proof(): void
    {
        config(['admin.username' => 'admin']);
        $this->withSession(['admin_authenticated' => true]);
        $infaq = $this->makeInfaq();

        $this->get(route('admin.infaq.show', $infaq))
            ->assertOk()
            ->assertSee('Verifikasi sebagai berhasil')
            ->assertDontSee('disabled', false)
            ->assertDontSee('Menunggu konfirmasi dan bukti transfer dari donor sebelum dapat diverifikasi');

        $this->patch(route('admin.infaq.verify', $infaq), [
            'confirmed_received' => '1',
            'verification_reference' => 'Mutasi rekening diperiksa admin',
        ])->assertSessionHas('success');

        $infaq->refresh();
        $this->assertSame(Infaq::STATUS_PAID, $infaq->payment_status);
        $this->assertSame('Mutasi rekening diperiksa admin', $infaq->verification_reference);
        $this->assertSame(25000, (int) Infaq::where('payment_status', Infaq::STATUS_PAID)->sum('amount'));
    }

    public function test_admin_can_reject_pending_payment_with_a_reason(): void
    {
        $this->withSession(['admin_authenticated' => true]);
        $infaq = $this->makeInfaq();

        $this->patch(route('admin.infaq.reject', $infaq), [
            'rejection_reason' => 'Nominal tidak ditemukan di mutasi.',
        ])->assertSessionHas('success');

        $this->assertSame(Infaq::STATUS_REJECTED, $infaq->fresh()->payment_status);
        $this->assertSame(0, Infaq::where('payment_status', Infaq::STATUS_PAID)->sum('amount'));
    }

    public function test_confirmation_requires_an_image_and_private_proof_is_admin_only(): void
    {
        $this->configureOfficialBank();
        Storage::fake('infaq_private');
        $infaq = $this->makeInfaq();

        $this->post(route('infaq.confirm', $infaq->public_token))
            ->assertSessionHasErrors('proof');
        $this->assertSame(Infaq::STATUS_PENDING, $infaq->fresh()->payment_status);

        $this->post(route('infaq.confirm', $infaq->public_token), [
            'proof' => UploadedFile::fake()->create('receipt.txt', 10, 'text/plain'),
        ])->assertSessionHasErrors('proof');

        $infaq->update(['proof_path' => 'private-receipt.png']);
        Storage::disk('infaq_private')->put('private-receipt.png', 'private');
        $this->get(route('admin.infaq.proof', $infaq))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_filter_transactions_and_export_csv(): void
    {
        $this->withSession(['admin_authenticated' => true]);
        $this->makeInfaq(['transaction_code' => 'INF-FILTER-MATCH']);
        $this->makeInfaq(['transaction_code' => 'INF-FILTER-OTHER']);

        $this->get(route('admin.infaq.index', ['search' => 'MATCH', 'status' => 'pending']))
            ->assertOk()
            ->assertSee('INF-FILTER-MATCH')
            ->assertDontSee('INF-FILTER-OTHER');
        $response = $this->get(route('admin.infaq.export', ['search' => 'MATCH']));
        $response->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('INF-FILTER-MATCH', $response->streamedContent());
    }

    private function makeInfaq(array $attributes = []): Infaq
    {
        return Infaq::create(array_merge([
            'transaction_code' => 'INF-'.Str::upper(Str::random(12)),
            'public_token' => (string) Str::uuid(),
            'idempotency_key' => (string) Str::uuid(),
            'donor_name' => 'Test Donor',
            'is_anonymous' => false,
            'amount' => 25000,
            'payment_method' => 'manual_transfer',
            'payment_status' => Infaq::STATUS_PENDING,
        ], $attributes));
    }

    private function configureOfficialBank(): void
    {
        config([
            'infaq.bank_name' => 'Bank Resmi',
            'infaq.account_number' => '123456789',
            'infaq.account_holder' => 'NU Karangpundung',
        ]);
    }

    private function fakePng(string $name): UploadedFile
    {
        $contents = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADUlEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC',
            true,
        );

        return UploadedFile::fake()->createWithContent($name, $contents);
    }
}
