@extends('frontend.layouts.app')

@section('title', 'Agenda Kegiatan')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center py-4">
        <h2 class="fw-bold text-success">Agenda & Kegiatan</h2>
        <p class="text-muted">Jadwal kegiatan, acara, dan program NU Karangpundung.</p>
        <hr class="w-25 mx-auto bg-success">
    </div>
</div>

<div class="row mb-4">
    @forelse($agendas as $agenda)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                @if($agenda->foto)
                    <img src="{{ asset('storage/' . $agenda->foto) }}" class="card-img-top" alt="{{ $agenda->judul }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fa-solid fa-image fa-3x"></i>
                    </div>
                @endif
                <div class="card-body">
                    <span class="badge bg-success mb-2">{{ $agenda->status }}</span>
                    <h5 class="card-title fw-bold">{{ $agenda->judul }}</h5>
                    <p class="text-muted small mb-2"><i class="fa-solid fa-calendar me-1"></i> {{ $agenda->tanggal }} | <i class="fa-solid fa-clock me-1"></i> {{ $agenda->waktu }}</p>
                    <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1"></i> {{ $agenda->lokasi }}</p>
                    <p class="card-text">{{ \Illuminate\Support\Str::limit($agenda->deskripsi, 80) }}</p>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <a href="{{ route('agenda.detail', $agenda->id) }}" class="btn btn-sm btn-success w-100">Detail Agenda</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">Belum ada agenda kegiatan yang tersedia.</div>
        </div>
    @endforelse
</div>

<div class="row">
    <div class="col-12 d-flex justify-content-center">
        {{ $agendas->links() }}
    </div>
</div>
@endsection