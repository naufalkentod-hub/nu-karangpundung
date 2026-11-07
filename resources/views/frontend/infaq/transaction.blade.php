@extends('frontend.layouts.app')

@section('title', 'Status Transaksi Infaq')

@section('content')
<div class="row justify-content-center py-4 py-md-5">
    <div class="col-lg-8 col-xl-7">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="p-4 p-md-5 text-white" style="background: #154734;">
                <p class="small text-uppercase fw-bold mb-2" style="color: #d8e88d;">Status transaksi</p>
                <h1 class="h2 fw-bold mb-2">Permohonan infaq tersimpan</h1>
                <p class="mb-0 text-white-50">Simpan kode transaksi ini untuk komunikasi dengan pengurus.</p>
            </div>
            <div class="card-body p-4 p-md-5">
                @if(session('success'))
                    <div class="alert alert-success" role="status">{{ session('success') }}</div>
                @endif
                @if(session('status_notice'))
                    <div class="alert alert-info" role="status">{{ session('status_notice') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <p class="small text-muted mb-1">Kode transaksi</p>
                        <p class="fw-bold mb-0">{{ $infaq->transaction_code }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="small text-muted mb-1">Nominal</p>
                        <p class="fw-bold text-success mb-0">Rp{{ number_format($infaq->amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-12">
                        <p class="small text-muted mb-1">Status</p>
                        @if($infaq->payment_status === 'paid')
                            <span class="badge text-bg-success">Terverifikasi</span>
                            <p class="small text-muted mt-2 mb-0">Pembayaran telah diverifikasi oleh pengurus.</p>
                        @elseif($infaq->payment_status === 'rejected')
                            <span class="badge text-bg-danger">Ditolak</span>
                            <p class="small text-muted mt-2 mb-0">Silakan hubungi pengurus dengan menyebutkan kode transaksi di atas.</p>
                        @elseif($infaq->manual_confirmation_at)
                            <span class="badge text-bg-warning">Menunggu verifikasi admin</span>
                            <p class="small text-muted mt-2 mb-0">Konfirmasi Anda sudah diterima. Pengurus akan memeriksa pembayaran.</p>
                        @else
                            <span class="badge text-bg-secondary">Menunggu instruksi pembayaran</span>
                            <p class="small text-muted mt-2 mb-0">Permohonan sudah diterima. Pengurus akan memberikan instruksi pembayaran resmi. Anda belum perlu mengirim uang.</p>
                        @endif
                    </div>
                </div>

                @if($infaq->payment_status === 'pending')
                    @if($bank['configured'])
                        <div class="rounded-3 p-4 mb-4" style="background: #f0f3e9;">
                            <h2 class="h5 fw-bold text-success">Pembayaran transfer manual</h2>
                            <p class="mb-3">Transfer sesuai nominal berikut ke rekening resmi, lalu unggah bukti transfer.</p>
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Bank</dt><dd class="col-sm-8">{{ $bank['name'] }}</dd>
                                <dt class="col-sm-4">Nomor rekening</dt>
                                <dd class="col-sm-8 fw-bold">
                                    <span id="account-number">{{ $bank['account_number'] }}</span>
                                    <button type="button" id="copy-account" class="btn btn-sm btn-outline-success ms-2" aria-label="Salin nomor rekening">
                                        <i class="fa-regular fa-copy" aria-hidden="true"></i> Salin
                                    </button>
                                </dd>
                                <dt class="col-sm-4">Atas nama</dt><dd class="col-sm-8">{{ $bank['account_holder'] }}</dd>
                                <dt class="col-sm-4">Nominal transfer</dt><dd class="col-sm-8 fw-bold text-success">Rp{{ number_format($infaq->amount, 0, ',', '.') }}</dd>
                                <dt class="col-sm-4">Kode transaksi</dt><dd class="col-sm-8 fw-bold">{{ $infaq->transaction_code }}</dd>
                            </dl>
                        </div>
                        <form action="{{ route('infaq.confirm', $infaq->public_token) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                            @csrf
                            <label for="proof" class="form-label fw-semibold">Bukti transfer (JPG, PNG, WebP; maksimal 4 MB)</label>
                            <input type="file" id="proof" name="proof" class="form-control mb-3" accept="image/jpeg,image/png,image/webp" required>
                            <button class="btn btn-success" type="submit">
                                <i class="fa-solid fa-check me-2" aria-hidden="true"></i>Saya sudah transfer
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <strong>Instruksi pembayaran belum tersedia.</strong> Permohonan Anda tercatat dan menunggu pengurus mengonfigurasi rekening resmi. Jangan transfer sebelum menerima instruksi resmi.
                        </div>
                    @endif
                @endif

                <div class="d-flex flex-wrap gap-2 border-top pt-4">
                    <a href="{{ route('infaq') }}" class="btn btn-outline-success">Kembali ke halaman infaq</a>
                    <a href="{{ route('beranda') }}" class="btn btn-light">Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>
@if($bank['configured'] && $infaq->payment_status === 'pending')
<script>
    document.getElementById('copy-account')?.addEventListener('click', async function () {
        const accountNumber = document.getElementById('account-number').textContent.trim();
        try {
            await navigator.clipboard.writeText(accountNumber);
            this.innerHTML = '<i class="fa-solid fa-check" aria-hidden="true"></i> Tersalin';
        } catch {
            window.prompt('Salin nomor rekening berikut:', accountNumber);
        }
    });
</script>
@endif
@endsection
