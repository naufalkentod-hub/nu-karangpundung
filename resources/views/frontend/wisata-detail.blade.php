@extends('frontend.layouts.app')

@section('title', $wisata->nama)

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('beranda') }}" class="text-success text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('wisata') }}" class="text-success text-decoration-none">Wisata</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>

        <div class="card shadow-sm border-0">
            @if($wisata->foto)
                <img src="{{ asset('storage/' . $wisata->foto) }}" class="card-img-top" alt="{{ $wisata->nama }}" style="max-height: 400px; object-fit: cover;">
            @endif
            <div class="card-body p-4">
                <h2 class="fw-bold mb-3">{{ $wisata->nama }}</h2>
                <div class="text-muted mb-4 pb-2 border-bottom">
                    <span class="me-3"><i class="fa-solid fa-location-dot me-1 text-success"></i> {{ $wisata->lokasi }}</span>
                    <span><i class="fa-solid fa-clock me-1 text-success"></i> {{ $wisata->jam_buka }}</span>
                </div>
                <h5 class="fw-bold text-success mb-2">Deskripsi Destinasi</h5>
                <div class="content lh-lg text-secondary mb-4">
                    {!! nl2br(e($wisata->deskripsi)) !!}
                </div>

                <h5 class="fw-bold text-success mb-2">Fasilitas</h5>
                <p class="text-secondary mb-4">{{ $wisata->fasilitas }}</p>

                <div class="mt-4 pt-3 border-top">
                    <a href="{{ route('wisata') }}" class="btn btn-outline-success"><i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Daftar Wisata</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection