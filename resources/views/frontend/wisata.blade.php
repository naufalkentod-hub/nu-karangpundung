@extends('frontend.layouts.app')

@section('title', 'Wisata Desa')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center py-4">
        <h2 class="fw-bold text-success">Destinasi Wisata Desa</h2>
        <p class="text-muted">Jelajahi keindahan alam, potensi lokal, dan tempat menarik di Desa Karangpundung.</p>
        <hr class="w-25 mx-auto bg-success">
    </div>
</div>

<div class="row mb-4">
    @forelse($wisatas as $wisata)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                @if($wisata->foto)
                    <img src="{{ asset('storage/' . $wisata->foto) }}" class="card-img-top" alt="{{ $wisata->nama }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fa-solid fa-image fa-3x"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title fw-bold">{{ $wisata->nama }}</h5>
                    <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1"></i> {{ $wisata->lokasi }}</p>
                    <p class="text-muted small mb-2"><i class="fa-solid fa-clock me-1"></i> {{ $wisata->jam_buka }}</p>
                    <p class="card-text">{{ \Illuminate\Support\Str::limit($wisata->deskripsi, 80) }}</p>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <a href="{{ route('wisata.detail', $wisata->id) }}" class="btn btn-sm btn-outline-success w-100">Jelajahi Wisata</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">Belum ada destinasi wisata yang tersedia.</div>
        </div>
    @endforelse
</div>

<div class="row">
    <div class="col-12 d-flex justify-content-center">
        {{ $wisatas->links() }}
    </div>
</div>
@endsection