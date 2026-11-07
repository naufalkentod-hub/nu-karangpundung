@extends('frontend.layouts.app')

@section('title', 'Beranda')

@section('content')
<section class="home-hero">
    <img class="home-hero-photo" src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=2000&q=85" alt="Sekelompok anak muda menikmati kebersamaan di tepi pantai" fetchpriority="high">
    <div class="home-hero-inner">
        <div class="hero-copy">
            <p class="hero-kicker">Pelajar bergerak, desa bertumbuh</p>
            <h1 class="hero-title">NU<br>Karangpundung</h1>
            <p class="hero-description">Ruang untuk belajar, berkhidmat, dan bertumbuh bersama warga serta nahdliyin Karangpundung.</p>
            <div class="hero-actions">
                <a href="{{ route('tentang') }}" class="hero-primary">Kenali organisasi <i class="fa-solid fa-arrow-right ms-2" aria-hidden="true"></i></a>
                <a href="{{ route('agenda') }}" class="hero-secondary">Lihat kegiatan <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
    <span class="hero-caption">Kebersamaan adalah awal pergerakan</span>
</section>

<section class="home-stats" aria-label="Ringkasan NU Karangpundung">
    <div class="row row-cols-2 row-cols-lg-4 g-0">
        <div class="col"><div class="stat-item"><span class="stat-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span><div><p class="stat-number">{{ $stats['anggota'] ?? 0 }}</p><p class="stat-label">Anggota</p></div></div></div>
        <div class="col"><div class="stat-item"><span class="stat-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></span><div><p class="stat-number">{{ $stats['kegiatan'] ?? 0 }}</p><p class="stat-label">Kegiatan</p></div></div></div>
        <div class="col"><div class="stat-item"><span class="stat-icon"><i class="fa-solid fa-clock" aria-hidden="true"></i></span><div><p class="stat-number">{{ $stats['agenda'] ?? 0 }}</p><p class="stat-label">Agenda mendatang</p></div></div></div>
        <div class="col"><div class="stat-item"><span class="stat-icon"><i class="fa-solid fa-mountain-sun" aria-hidden="true"></i></span><div><p class="stat-number">{{ $stats['wisata'] ?? 0 }}</p><p class="stat-label">Destinasi wisata</p></div></div></div>
    </div>
</section>

<section class="home-section">
    <div class="section-heading">
        <div><p class="section-kicker">Tetap terhubung</p><h2 class="section-title">Agenda terbaru</h2></div>
        <a href="{{ route('agenda') }}" class="section-link">Semua agenda <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></a>
    </div>
    <div class="row g-3">
        @forelse($agendas as $agenda)
            <div class="col-md-6 col-xl-4">
                <article class="feature-card">
                    @if($agenda->foto)
                        <img src="{{ asset('storage/' . $agenda->foto) }}" class="feature-image" alt="{{ $agenda->judul }}" loading="lazy">
                    @else
                        <div class="feature-placeholder"><i class="fa-regular fa-image" aria-hidden="true"></i></div>
                    @endif
                    <div class="feature-body">
                        <div class="feature-meta">
                            <span class="feature-status">{{ $agenda->status }}</span>
                            <span>{{ date('d M Y', strtotime($agenda->tanggal)) }}</span>
                        </div>
                        <h3 class="feature-title">{{ $agenda->judul }}</h3>
                        <p class="feature-text"><i class="fa-solid fa-location-dot me-1" aria-hidden="true"></i>{{ $agenda->lokasi }}</p>
                        <p class="feature-text mt-2">{{ \Illuminate\Support\Str::limit($agenda->deskripsi, 90) }}</p>
                    </div>
                    <div class="feature-footer">
                        <a href="{{ route('agenda.detail', $agenda->id) }}" class="feature-button">Lihat detail <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12"><p class="empty-state mb-0">Belum ada agenda kegiatan yang diunggah.</p></div>
        @endforelse
    </div>
</section>

<section class="home-section pt-5">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 p-4 p-lg-5 rounded-3" style="background: #e8efe2; border: 1px solid #d7e3d3;">
        <div>
            <p class="section-kicker mb-2">Bergerak bersama</p>
            <h2 class="section-title mb-2">Dukung kegiatan NU Karangpundung</h2>
            <p class="text-secondary mb-0">Infaq Anda membantu kegiatan organisasi, sosial, dan pendidikan bagi warga serta nahdliyin Karangpundung.</p>
        </div>
        <a href="{{ route('infaq') }}" class="btn btn-success flex-shrink-0 px-4 py-2">
            <i class="fa-solid fa-hand-holding-heart me-2" aria-hidden="true"></i>Kenali program infaq
        </a>
    </div>
</section>

<section class="home-section pt-5">
    <div class="section-heading">
        <div><p class="section-kicker">Potensi lokal</p><h2 class="section-title">Jelajahi Karangpundung</h2></div>
        <a href="{{ route('wisata') }}" class="section-link">Semua wisata <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></a>
    </div>
    <div class="row g-3">
        @forelse($wisatas as $wisata)
            <div class="col-md-6 col-xl-4">
                <article class="feature-card">
                    @if($wisata->foto)
                        <img src="{{ asset('storage/' . $wisata->foto) }}" class="feature-image" alt="{{ $wisata->nama }}" loading="lazy">
                    @else
                        <div class="feature-placeholder"><i class="fa-solid fa-mountain-sun" aria-hidden="true"></i></div>
                    @endif
                    <div class="feature-body">
                        <div class="feature-meta">
                            <span><i class="fa-solid fa-location-dot me-1" aria-hidden="true"></i>{{ $wisata->lokasi }}</span>
                            <span>{{ $wisata->jam_buka ?? '24 Jam' }}</span>
                        </div>
                        <h3 class="feature-title">{{ $wisata->nama }}</h3>
                        <p class="feature-text">{{ \Illuminate\Support\Str::limit($wisata->deskripsi, 90) }}</p>
                    </div>
                    <div class="feature-footer">
                        <a href="{{ route('wisata.detail', $wisata->id) }}" class="feature-button">Jelajahi tempat <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12"><p class="empty-state mb-0">Belum ada destinasi wisata yang diunggah.</p></div>
        @endforelse
    </div>
</section>
@endsection