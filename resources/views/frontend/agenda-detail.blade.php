@extends('frontend.layouts.app')

@section('title', $agenda->judul)

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-lg-9">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('beranda') }}" class="text-success text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('agenda') }}" class="text-success text-decoration-none">Agenda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>

        <article class="card shadow-sm border-0 overflow-hidden">
            @if($agenda->foto)
                <img src="{{ asset('storage/' . $agenda->foto) }}" class="card-img-top" alt="{{ $agenda->judul }}" style="max-height: 420px; object-fit: cover;">
            @else
                <div class="bg-light text-secondary d-flex align-items-center justify-content-center" role="img" aria-label="Tidak ada gambar agenda" style="height: 240px;">
                    <i class="fa-solid fa-image fa-3x" aria-hidden="true"></i>
                </div>
            @endif

            <div class="card-body p-4 p-md-5">
                <span class="badge bg-success mb-3">{{ $agenda->status }}</span>
                <h1 class="h2 fw-bold mb-4">{{ $agenda->judul }}</h1>

                <div class="row g-3 mb-4 pb-4 border-bottom">
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1"><i class="fa-solid fa-calendar me-2 text-success"></i>Tanggal</div>
                        <div class="fw-semibold">{{ \Illuminate\Support\Carbon::parse($agenda->tanggal)->format('d-m-Y') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1"><i class="fa-solid fa-clock me-2 text-success"></i>Jam</div>
                        <div class="fw-semibold">{{ $agenda->waktu }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small mb-1"><i class="fa-solid fa-location-dot me-2 text-success"></i>Lokasi</div>
                        <div class="fw-semibold">{{ $agenda->lokasi }}</div>
                    </div>
                </div>

                <h2 class="h5 fw-bold text-success mb-3">Deskripsi Kegiatan</h2>
                <div class="lh-lg text-secondary">
                    {!! nl2br(e($agenda->deskripsi)) !!}
                </div>

                <div class="mt-4 pt-3 border-top">
                    <a href="{{ route('agenda') }}" class="btn btn-outline-success">
                        <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Daftar Agenda
                    </a>
                </div>
            </div>
        </article>
    </div>
</div>
@endsection
