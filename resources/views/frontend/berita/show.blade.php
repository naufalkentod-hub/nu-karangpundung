@extends('frontend.layouts.app')

@section('title', $berita->judul)

@section('content')
<article class="mx-auto" style="max-width: 860px;">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('beranda') }}" class="text-success text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('berita.index') }}" class="text-success text-decoration-none">Berita</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($berita->judul, 45) }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm overflow-hidden">
        @if($berita->gambar)
            <img src="{{ asset('storage/'.$berita->gambar) }}" class="w-100" alt="{{ $berita->judul }}" style="max-height: 480px; object-fit: cover;">
        @endif
        <div class="card-body p-4 p-md-5">
            <div class="feature-meta mb-3">
                <span class="feature-status">{{ $berita->kategori }}</span>
                <time datetime="{{ $berita->tanggal_publikasi->format('Y-m-d') }}">{{ $berita->tanggal_publikasi->format('d M Y') }}</time>
            </div>
            <h1 class="fw-bold mb-3">{{ $berita->judul }}</h1>
            <p class="lead text-secondary border-bottom pb-4 mb-4">{{ $berita->ringkasan }}</p>
            <div class="text-secondary lh-lg">{!! nl2br(e($berita->isi)) !!}</div>
            <div class="mt-5 pt-3 border-top">
                <a href="{{ route('berita.index') }}" class="btn btn-outline-success"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Kembali ke Berita</a>
            </div>
        </div>
    </div>
</article>
@endsection
