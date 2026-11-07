@extends('admin.layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.infaq.index') }}" class="text-success text-decoration-none small fw-semibold">
        <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i>Kembali ke daftar infaq
    </a>
    <h1 class="h3 fw-bold text-success mt-3 mb-1">Detail Transaksi</h1>
    <p class="text-muted mb-0">{{ $infaq->transaction_code }}</p>
</div>

@if(session('success'))
    <div class="alert alert-success" role="status">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card p-4 h-100">
            <h2 class="h5 fw-bold mb-4">Informasi transaksi</h2>
            <dl class="row mb-0">
                <dt class="col-sm-5">Kode transaksi</dt><dd class="col-sm-7">{{ $infaq->transaction_code }}</dd>
                <dt class="col-sm-5">Nama donatur</dt><dd class="col-sm-7">{{ $infaq->is_anonymous ? 'Donatur anonim' : $infaq->donor_name }}</dd>
                <dt class="col-sm-5">Nominal</dt><dd class="col-sm-7 fw-bold">Rp{{ number_format($infaq->amount, 0, ',', '.') }}</dd>
                <dt class="col-sm-5">Metode pembayaran</dt><dd class="col-sm-7">{{ $infaq->payment_method }}</dd>
                <dt class="col-sm-5">Status</dt><dd class="col-sm-7">{{ $infaq->payment_status }}</dd>
                <dt class="col-sm-5">Dibuat</dt><dd class="col-sm-7">{{ $infaq->created_at->format('d/m/Y H:i:s') }}</dd>
                <dt class="col-sm-5">Konfirmasi donor</dt><dd class="col-sm-7">{{ $infaq->manual_confirmation_at?->format('d/m/Y H:i:s') ?? 'Belum dikonfirmasi' }}</dd>
                @if($infaq->proof_path)
                    <dt class="col-sm-5">Bukti transfer</dt>
                    <dd class="col-sm-7"><a href="{{ route('admin.infaq.proof', $infaq) }}" class="btn btn-sm btn-outline-success" target="_blank" rel="noopener">Lihat bukti privat</a></dd>
                @endif
                <dt class="col-sm-5">Waktu dibayar</dt><dd class="col-sm-7">{{ $infaq->paid_at?->format('d/m/Y H:i:s') ?? '-' }}</dd>
                <dt class="col-sm-5">Pesan atau doa</dt><dd class="col-sm-7 text-break">{{ $infaq->message ?: '-' }}</dd>
                @if($infaq->verification_reference)
                    <dt class="col-sm-5">Referensi verifikasi</dt><dd class="col-sm-7">{{ $infaq->verification_reference }}</dd>
                @endif
                @if($infaq->rejection_reason)
                    <dt class="col-sm-5">Alasan penolakan</dt><dd class="col-sm-7 text-break">{{ $infaq->rejection_reason }}</dd>
                @endif
                @if($infaq->reviewed_by)
                    <dt class="col-sm-5">Diperiksa oleh</dt><dd class="col-sm-7">{{ $infaq->reviewed_by }}</dd>
                @endif
            </dl>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card p-4 mb-4">
            <h2 class="h5 fw-bold text-success mb-2">Verifikasi pembayaran</h2>
            <p class="small text-muted">Pastikan dana benar-benar masuk ke rekening resmi sebelum menandai lunas. Konfirmasi donor saja tidak membuktikan pembayaran.</p>
            @if(in_array($infaq->payment_status, ['pending', 'pending_verification'], true))
                <form method="POST" action="{{ route('admin.infaq.verify', $infaq) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="verification_reference" class="form-label">Referensi pemeriksaan</label>
                        <input id="verification_reference" name="verification_reference" class="form-control" value="{{ old('verification_reference') }}" maxlength="120" required>
                        <div class="form-text">Contoh: tanggal/waktu mutasi atau nomor catatan internal. Jangan masukkan kata sandi perbankan.</div>
                    </div>
                    <div class="form-check mb-3">
                        <input id="confirmed_received" name="confirmed_received" class="form-check-input" type="checkbox" value="1" required>
                        <label for="confirmed_received" class="form-check-label">Saya telah memeriksa dan memastikan dana masuk ke rekening resmi.</label>
                    </div>
                    <button class="btn btn-success w-100" type="submit">
                        Verifikasi sebagai berhasil
                    </button>
                </form>
                <hr>
                <form method="POST" action="{{ route('admin.infaq.reject', $infaq) }}" onsubmit="return confirm('Tolak transaksi ini?')">
                    @csrf
                    @method('PATCH')
                    <label for="rejection_reason" class="form-label">Alasan penolakan</label>
                    <textarea id="rejection_reason" name="rejection_reason" class="form-control mb-3" rows="3" maxlength="1000" required>{{ old('rejection_reason') }}</textarea>
                    <button class="btn btn-outline-danger w-100" type="submit">Tolak transaksi</button>
                </form>
            @else
                <div class="alert alert-light mb-0">Transaksi sudah diproses dan tidak dapat diverifikasi ulang.</div>
            @endif
        </div>
    </div>
</div>
@endsection
