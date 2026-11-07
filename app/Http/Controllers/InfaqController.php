<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInfaqRequest;
use App\Models\Infaq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InfaqController extends Controller
{
    public function index(): View
    {
        $paidInfaqs = Infaq::where('payment_status', Infaq::STATUS_PAID);

        return view('frontend.infaq.index', [
            'totalAmount' => (int) (clone $paidInfaqs)->sum('amount'),
            'paidCount' => (clone $paidInfaqs)->count(),
            'recentInfaqs' => (clone $paidInfaqs)
                ->select(['id', 'amount', 'paid_at'])
                ->latest('paid_at')
                ->take(8)
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('frontend.infaq.create', [
            'idempotencyKey' => old('idempotency_key', (string) Str::uuid()),
            'bank' => $this->bankDetails(),
        ]);
    }

    public function store(StoreInfaqRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $anonymous = (bool) ($data['is_anonymous'] ?? false);

        $infaq = Infaq::firstOrCreate(
            ['idempotency_key' => $data['idempotency_key']],
            [
                'transaction_code' => 'INF-'.Str::upper(Str::random(16)),
                'public_token' => (string) Str::uuid(),
                'donor_name' => $anonymous ? null : $data['donor_name'],
                'is_anonymous' => $anonymous,
                'amount' => (int) $data['amount'],
                'message' => $data['message'] ?? null,
                'payment_method' => $data['payment_method'],
                'payment_status' => Infaq::STATUS_PENDING,
            ],
        );

        return redirect()
            ->route('infaq.transaction', ['token' => $infaq->public_token])
            ->with('success', 'Permohonan infaq tersimpan. Status transaksi masih menunggu pembayaran dan verifikasi pengurus.');
    }

    public function transaction(string $token): Response
    {
        $infaq = Infaq::where('public_token', $token)->firstOrFail();

        return response()
            ->view('frontend.infaq.transaction', [
                'infaq' => $infaq,
                'bank' => $this->bankDetails(),
            ])
            ->header('Referrer-Policy', 'no-referrer')
            ->header('Cache-Control', 'no-store, private');
    }

    public function confirm(Request $request, string $token): RedirectResponse
    {
        if (! $this->bankDetails()['configured']) {
            return back()->with(
                'status_notice',
                'Konfirmasi belum tersedia karena informasi rekening resmi belum dikonfigurasi.',
            );
        }

        $request->validate([
            'proof' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:4096'],
        ], [
            'proof.required' => 'Unggah bukti transfer agar pengurus dapat memeriksa pembayaran.',
            'proof.image' => 'Bukti transfer harus berupa gambar.',
            'proof.mimes' => 'Format bukti transfer harus JPG, PNG, atau WebP.',
            'proof.max' => 'Ukuran bukti transfer maksimal 4 MB.',
        ]);

        $confirmed = DB::transaction(function () use ($request, $token): bool {
            $infaq = Infaq::where('public_token', $token)->lockForUpdate()->firstOrFail();

            if ($infaq->payment_status !== Infaq::STATUS_PENDING || $infaq->amount < 1) {
                return false;
            }

            $proofPath = $request->file('proof')->store('', 'infaq_private');
            $infaq->update([
                'payment_status' => Infaq::STATUS_PENDING_VERIFICATION,
                'manual_confirmation_at' => now(),
                'proof_path' => $proofPath,
            ]);

            return true;
        });

        return back()->with(
            $confirmed ? 'success' : 'status_notice',
            $confirmed
                ? 'Konfirmasi dan bukti transfer Anda tercatat. Pembayaran akan diperiksa oleh pengurus.'
                : 'Konfirmasi tidak diperlukan lagi karena sudah tercatat atau transaksi telah diproses.',
        );
    }

    private function bankDetails(): array
    {
        $bank = [
            'name' => config('infaq.bank_name'),
            'account_number' => config('infaq.account_number'),
            'account_holder' => config('infaq.account_holder'),
        ];
        $bank['configured'] = collect($bank)->every(fn ($value) => filled($value));

        return $bank;
    }
}
