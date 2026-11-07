<?php

namespace App\Http\Controllers;

use App\Models\Infaq;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminInfaqController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);
        $stats = [
            'paid_amount' => (int) Infaq::where('payment_status', Infaq::STATUS_PAID)->sum('amount'),
            'paid_count' => Infaq::where('payment_status', Infaq::STATUS_PAID)->count(),
            'pending_count' => Infaq::where('payment_status', Infaq::STATUS_PENDING)->count(),
            'verification_count' => Infaq::where('payment_status', Infaq::STATUS_PENDING_VERIFICATION)->count(),
            'rejected_count' => Infaq::where('payment_status', Infaq::STATUS_REJECTED)->count(),
        ];
        $infaqs = $this->filteredQuery($filters)->latest()->paginate(20)->withQueryString();

        return view('admin.infaq.index', compact('filters', 'stats', 'infaqs'));
    }

    public function show(Infaq $infaq): View
    {
        return view('admin.infaq.show', compact('infaq'));
    }

    public function proof(Infaq $infaq)
    {
        abort_unless($infaq->proof_path && Storage::disk('infaq_private')->exists($infaq->proof_path), 404);

        return Storage::disk('infaq_private')->response(
            $infaq->proof_path,
            $infaq->transaction_code.'-bukti-transfer',
            ['Cache-Control' => 'private, no-store'],
        );
    }

    public function verify(Request $request, Infaq $infaq)
    {
        $data = $request->validate([
            'confirmed_received' => ['accepted'],
            'verification_reference' => ['required', 'string', 'min:3', 'max:120'],
        ], [
            'confirmed_received.accepted' => 'Konfirmasi bahwa dana sudah masuk ke rekening resmi wajib diberikan.',
            'verification_reference.required' => 'Catatan referensi pemeriksaan wajib diisi.',
        ]);

        $verified = DB::transaction(function () use ($infaq, $data): bool {
            $locked = Infaq::whereKey($infaq->getKey())->lockForUpdate()->firstOrFail();

            if (
                ! in_array($locked->payment_status, [
                    Infaq::STATUS_PENDING,
                    Infaq::STATUS_PENDING_VERIFICATION,
                ], true)
                || $locked->amount < 1
            ) {
                return false;
            }

            $locked->update([
                'payment_status' => Infaq::STATUS_PAID,
                'paid_at' => now(),
                'verification_reference' => $data['verification_reference'],
                'reviewed_by' => config('admin.username'),
            ]);

            return true;
        });

        return $verified
            ? back()->with('success', 'Pembayaran berhasil diverifikasi dan tercatat sebagai lunas.')
            : back()->withErrors(['payment' => 'Transaksi harus berstatus menunggu dan memiliki nominal yang valid sebelum dapat diverifikasi.']);
    }

    public function reject(Request $request, Infaq $infaq)
    {
        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $rejected = DB::transaction(function () use ($infaq, $data): bool {
            $locked = Infaq::whereKey($infaq->getKey())->lockForUpdate()->firstOrFail();

            if (! in_array($locked->payment_status, [
                Infaq::STATUS_PENDING,
                Infaq::STATUS_PENDING_VERIFICATION,
            ], true)) {
                return false;
            }

            $locked->update([
                'payment_status' => Infaq::STATUS_REJECTED,
                'rejection_reason' => $data['rejection_reason'],
                'reviewed_by' => config('admin.username'),
            ]);

            return true;
        });

        return $rejected
            ? back()->with('success', 'Transaksi ditolak dengan alasan yang tercatat.')
            : back()->withErrors(['payment' => 'Hanya transaksi yang masih menunggu yang dapat ditolak.']);
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->filteredQuery($this->validatedFilters($request));

        return response()->streamDownload(function () use ($query): void {
            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, [
                'Kode transaksi', 'Tanggal dibuat', 'Nama donor', 'Anonim', 'Nominal',
                'Metode', 'Status', 'Konfirmasi donor', 'Tanggal dibayar',
                'Referensi verifikasi', 'Alasan penolakan',
            ]);

            foreach ($query->orderByDesc('created_at')->cursor() as $infaq) {
                $row = [
                    $infaq->transaction_code,
                    $infaq->created_at?->format('Y-m-d H:i:s'),
                    $infaq->is_anonymous ? 'Donatur anonim' : $infaq->donor_name,
                    $infaq->is_anonymous ? 'Ya' : 'Tidak',
                    (string) $infaq->amount,
                    $infaq->payment_method,
                    $infaq->payment_status,
                    $infaq->manual_confirmation_at?->format('Y-m-d H:i:s'),
                    $infaq->paid_at?->format('Y-m-d H:i:s'),
                    $infaq->verification_reference,
                    $infaq->rejection_reason,
                ];

                fputcsv($stream, array_map($this->safeCsvCell(...), $row));
            }

            fclose($stream);
        }, 'laporan-infaq-'.now()->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', Rule::in([
                Infaq::STATUS_PENDING,
                Infaq::STATUS_PENDING_VERIFICATION,
                Infaq::STATUS_PAID,
                Infaq::STATUS_REJECTED,
            ])],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);
    }

    private function filteredQuery(array $filters): Builder
    {
        return Infaq::query()
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('transaction_code', 'like', '%'.$search.'%')
                        ->orWhere('donor_name', 'like', '%'.$search.'%');
                });
            })
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('payment_status', $status))
            ->when($filters['from'] ?? null, fn (Builder $query, string $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['to'] ?? null, fn (Builder $query, string $date) => $query->whereDate('created_at', '<=', $date));
    }

    private function safeCsvCell(?string $value): string
    {
        $value ??= '';

        return preg_match('/^[\s]*[=+\-@]/u', $value) ? "'".$value : $value;
    }
}
