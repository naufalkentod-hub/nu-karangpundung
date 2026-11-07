@extends('frontend.layouts.app')

@section('title', 'Berita')

@section('content')
<section class="mb-4">
    <div class="p-4 p-md-5 rounded-3" style="background: linear-gradient(120deg, #154734, #237b53); color: #fff;">
        <p class="mb-2 small fw-bold text-uppercase" style="letter-spacing: .12em; color: #d8e88d;">Kabar Karangpundung</p>
        <h1 class="fw-bold mb-2">Berita NU Karangpundung</h1>
        <p class="mb-0 opacity-75">Informasi, kegiatan, dan cerita terbaru dari NU Karangpundung.</p>
    </div>
</section>

<section class="mb-4">
    <form method="GET" action="{{ route('berita.index') }}" class="card border-0 shadow-sm p-3 p-md-4">
        <div class="row g-2">
            <div class="col-md">
                <label for="search" class="visually-hidden">Cari berita</label>
                <input id="search" name="search" type="search" class="form-control" value="{{ request('search') }}" placeholder="Cari judul atau ringkasan berita...">
            </div>
            <div class="col-md-4">
                <label for="kategori" class="visually-hidden">Kategori</label>
                <select id="kategori" name="kategori" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(request('kategori') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-auto d-flex gap-2">
                <button class="btn btn-success px-4" type="submit"><i class="fa-solid fa-magnifying-glass me-1" aria-hidden="true"></i> Cari</button>
                @if(request()->hasAny(['search', 'kategori']))
                    <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </div>
    </form>
</section>

<section aria-label="Daftar berita">
    <div class="row g-4">
        @forelse($beritas as $berita)
            <div class="col-md-6 col-xl-4">
                <article class="feature-card d-flex flex-column">
                    @if($berita->gambar)
                        <img src="{{ asset('storage/'.$berita->gambar) }}" class="feature-image" alt="{{ $berita->judul }}" loading="lazy">
                    @else
                        <div class="feature-placeholder"><i class="fa-regular fa-newspaper" aria-hidden="true"></i></div>
                    @endif
                    <div class="feature-body flex-grow-1">
                        <div class="feature-meta">
                            <span class="feature-status">{{ $berita->kategori }}</span>
                            <time datetime="{{ $berita->tanggal_publikasi->format('Y-m-d') }}">{{ $berita->tanggal_publikasi->format('d M Y') }}</time>
                        </div>
                        <h2 class="feature-title">{{ $berita->judul }}</h2>
                        <p class="feature-text">{{ $berita->ringkasan }}</p>
                    </div>
                    <div class="feature-footer">
                        <a href="{{ route('berita.show', $berita->slug) }}" class="feature-button">Baca Selengkapnya <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <p class="empty-state mb-0">Belum ada berita yang cocok dengan pencarian Anda.</p>
            </div>
        @endforelse
    </div>
    <div class="d-flex justify-content-center mt-4">{{ $beritas->links() }}</div>
</section>
@endsection
