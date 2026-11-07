@extends('frontend.layouts.app')

@section('title', 'Infaq')

@section('content')
<section class="py-3 py-md-4">
    <div class="rounded-4 p-4 p-md-5 mb-4" style="background: linear-gradient(125deg, #154734, #237b53); color: #fff;">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="badge rounded-pill px-3 py-2 mb-3" style="background: #dcebd8; color: #154734;">Berbagi manfaat, tumbuh bersama</span>
                <h1 class="display-6 fw-bold mb-3">Infaq NU Karangpundung</h1>
                <p class="mb-0" style="max-width: 680px; color: rgba(255,255,255,.86);">
                    Infaq Anda mendukung ruang belajar dan kegiatan pelajar agar terus tumbuh, berkhidmat, serta memberi manfaat bagi masyarakat.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('infaq.create') }}" class="btn btn-lg px-4 fw-semibold" style="color: #154734; background: #d8e88d;">
                    <i class="fa-solid fa-heart me-2" aria-hidden="true"></i>Infaq Sekarang
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <span class="d-grid rounded-circle p-3 text-success bg-success-subtle"><i class="fa-solid fa-wallet fa-lg" aria-hidden="true"></i></span>
                    <div>
                        <p class="text-muted small mb-1">Dana terverifikasi</p>
                        <p class="h3 fw-bold text-success mb-0">Rp{{ number_format($totalAmount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <span class="d-grid rounded-circle p-3 text-success bg-success-subtle"><i class="fa-solid fa-circle-check fa-lg" aria-hidden="true"></i></span>
                    <div>
                        <p class="text-muted small mb-1">Infaq berhasil diverifikasi</p>
                        <p class="h3 fw-bold text-success mb-0">{{ number_format($paidCount, 0, ',', '.') }} transaksi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card h-100 border-0 rounded-4" style="background: #f0f3e9;">
                <div class="card-body p-4">
                    <h2 class="h4 fw-bold text-success mb-3">Menyalurkan kebaikan</h2>
                    <p class="text-secondary">Dana infaq digunakan untuk mendukung kegiatan organisasi, program sosial, pendidikan, serta kebutuhan lain yang telah disetujui pengurus.</p>
                    <p class="text-secondary mb-0">Setiap pembayaran menunggu pemeriksaan pengurus. Hanya transaksi yang telah diverifikasi yang masuk ke total dana dan riwayat publik.</p>
                    <a href="{{ route('infaq.create') }}" class="btn btn-success mt-4">
                        Mulai berinfaq <i class="fa-solid fa-arrow-right ms-2" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="text-success small fw-bold text-uppercase mb-1">Transparansi</p>
                    <h2 class="h4 fw-bold mb-0">Infaq terbaru</h2>
                </div>
            </div>
            <div class="card border-0 shadow-sm rounded-4">
                <div class="list-group list-group-flush rounded-4">
                    @forelse($recentInfaqs as $infaq)
                        <div class="list-group-item px-4 py-3 d-flex align-items-center justify-content-between gap-3">
                            <div>
                                <p class="fw-semibold mb-1">Donatur</p>
                                <p class="small text-muted mb-0">Terverifikasi {{ $infaq->paid_at?->format('d/m/Y') }}</p>
                            </div>
                            <span class="fw-bold text-success text-nowrap">Rp{{ number_format($infaq->amount, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">Belum ada transaksi infaq yang terverifikasi.</div>
                    @endforelse
                </div>
            </div>
            <p class="small text-muted mt-2 mb-0"><i class="fa-solid fa-shield-halved me-1" aria-hidden="true"></i>Identitas dan pesan donatur tidak ditampilkan di halaman publik.</p>
        </div>
    </div>
</section>
@endsection
