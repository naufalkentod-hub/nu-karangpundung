@extends('admin.layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-success mb-1">Pengelolaan Infaq</h1>
        <p class="text-muted mb-0">Periksa pembayaran manual sebelum mengubah status transaksi.</p>
    </div>
    <a href="{{ route('admin.infaq.export', request()->query()) }}" class="btn btn-outline-success">
        <i class="fa-solid fa-file-csv me-2" aria-hidden="true"></i>Ekspor CSV
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success" role="status">{{ session('success') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-xl col-sm-6"><div class="card p-3 border-start border-success border-4 h-100"><p class="small text-muted mb-1">Dana terverifikasi</p><h3 class="h5 fw-bold mb-0">Rp{{ number_format($stats['paid_amount'], 0, ',', '.') }}</h3></div></div>
    <div class="col-xl col-sm-6"><div class="card p-3 border-start border-primary border-4 h-100"><p class="small text-muted mb-1">Berhasil</p><h3 class="h5 fw-bold mb-0">{{ number_format($stats['paid_count']) }} transaksi</h3></div></div>
    <div class="col-xl col-sm-6"><div class="card p-3 border-start border-warning border-4 h-100"><p class="small text-muted mb-1">Menunggu instruksi / transfer</p><h3 class="h5 fw-bold mb-0">{{ number_format($stats['pending_count']) }} transaksi</h3></div></div>
    <div class="col-xl col-sm-6"><div class="card p-3 border-start border-info border-4 h-100"><p class="small text-muted mb-1">Perlu verifikasi</p><h3 class="h5 fw-bold mb-0">{{ number_format($stats['verification_count']) }} transaksi</h3></div></div>
    <div class="col-xl col-sm-6"><div class="card p-3 border-start border-danger border-4 h-100"><p class="small text-muted mb-1">Ditolak</p><h3 class="h5 fw-bold mb-0">{{ number_format($stats['rejected_count']) }} transaksi</h3></div></div>
</div>

<div class="card p-3 p-lg-4 mb-4">
    <form method="GET" action="{{ route('admin.infaq.index') }}" class="row g-3 align-items-end">
        <div class="col-lg-4">
            <label for="search" class="form-label">Nama atau kode transaksi</label>
            <input id="search" class="form-control" type="search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="120">
        </div>
        <div class="col-sm-6 col-lg-2">
            <label for="status" class="form-label">Status</label>
            <select id="status" class="form-select" name="status">
                <option value="">Semua status</option>
                @foreach(['pending' => 'Menunggu instruksi / transfer', 'pending_verification' => 'Perlu verifikasi', 'paid' => 'Berhasil', 'rejected' => 'Ditolak'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-6 col-lg-2">
            <label for="from" class="form-label">Dari tanggal</label>
            <input id="from" class="form-control" type="date" name="from" value="{{ $filters['from'] ?? '' }}">
        </div>
        <div class="col-sm-6 col-lg-2">
            <label for="to" class="form-label">Sampai tanggal</label>
            <input id="to" class="form-control" type="date" name="to" value="{{ $filters['to'] ?? '' }}">
        </div>
        <div class="col-sm-6 col-lg-2 d-flex gap-2">
            <button class="btn btn-success flex-grow-1" type="submit">Cari</button>
            <a href="{{ route('admin.infaq.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card p-3 p-lg-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Kode transaksi</th><th>Donatur</th><th>Nominal</th><th>Metode</th><th>Dibuat</th><th>Konfirmasi</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($infaqs as $infaq)
                    <tr>
                        <td class="fw-semibold text-nowrap">{{ $infaq->transaction_code }}</td>
                        <td>{{ $infaq->is_anonymous ? 'Donatur anonim' : $infaq->donor_name }}</td>
                        <td class="text-nowrap">Rp{{ number_format($infaq->amount, 0, ',', '.') }}</td>
                        <td>{{ $infaq->payment_method }}</td>
                        <td class="text-nowrap">{{ $infaq->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-nowrap">{{ $infaq->manual_confirmation_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>
                            @if($infaq->payment_status === 'paid')<span class="badge text-bg-success">Berhasil</span>
                            @elseif($infaq->payment_status === 'rejected')<span class="badge text-bg-danger">Ditolak</span>
                            @elseif($infaq->payment_status === 'pending_verification')<span class="badge text-bg-info">Perlu verifikasi</span>
                            @else<span class="badge text-bg-warning">Menunggu instruksi / transfer</span>@endif
                        </td>
                        <td class="text-end"><a href="{{ route('admin.infaq.show', $infaq) }}" class="btn btn-sm btn-outline-success">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada transaksi yang sesuai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $infaqs->links() }}</div>
</div>
@endsection
