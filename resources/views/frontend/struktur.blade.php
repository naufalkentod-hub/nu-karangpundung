@extends('frontend.layouts.app')

@section('title', 'Struktur Organisasi')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center py-4">
        <h2 class="fw-bold text-success">Struktur Kepengurusan</h2>
        <p class="text-muted">Susunan pengurus dan unsur organisasi NU Karangpundung.</p>
        <hr class="w-25 mx-auto bg-success">
    </div>
</div>

<!-- Pembina -->
<div class="row mb-5">
    <div class="col-12">
        <h4 class="fw-bold text-success mb-3 border-bottom pb-2"><i class="fa-solid fa-user-shield me-2"></i>Dewan Pembina</h4>
    </div>
    @forelse($pembina as $p)
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 text-center p-3 h-100">
                @if($p->foto)
                    <img src="{{ asset('storage/' . $p->foto) }}" class="rounded-circle mx-auto mb-3" alt="{{ $p->nama }}" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center text-white" style="width: 120px; height: 120px;">
                        <i class="fa-solid fa-user fa-3x"></i>
                    </div>
                @endif
                <h5 class="fw-bold mb-1">{{ $p->nama }}</h5>
                <p class="text-success small fw-semibold mb-2">{{ $p->jabatan }}</p>
                <p class="text-muted small mb-0">Periode: {{ $p->periode }}</p>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-light text-center border">Belum ada data pembina.</div></div>
    @endforelse
</div>

<!-- Ketua -->
<div class="row mb-5">
    <div class="col-12">
        <h4 class="fw-bold text-success mb-3 border-bottom pb-2"><i class="fa-solid fa-user-tie me-2"></i>Ketua Pimpinan</h4>
    </div>
    @forelse($ketua as $k)
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 text-center p-3 h-100 bg-light">
                @if($k->foto)
                    <img src="{{ asset('storage/' . $k->foto) }}" class="rounded-circle mx-auto mb-3" alt="{{ $k->nama }}" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center text-white" style="width: 120px; height: 120px;">
                        <i class="fa-solid fa-user fa-3x"></i>
                    </div>
                @endif
                <h5 class="fw-bold mb-1">{{ $k->nama }}</h5>
                <p class="text-success small fw-semibold mb-2">{{ $k->jabatan }}</p>
                <p class="text-muted small mb-0">Periode: {{ $k->periode }}</p>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-light text-center border">Belum ada data ketua.</div></div>
    @endforelse
</div>

<!-- Pengurus Inti -->
<div class="row mb-5">
    <div class="col-12">
        <h4 class="fw-bold text-success mb-3 border-bottom pb-2"><i class="fa-solid fa-users-gear me-2"></i>Pengurus Inti</h4>
    </div>
    @forelse($pengurusInti as $pi)
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 text-center p-3 h-100">
                @if($pi->foto)
                    <img src="{{ asset('storage/' . $pi->foto) }}" class="rounded-circle mx-auto mb-3" alt="{{ $pi->nama }}" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center text-white" style="width: 100px; height: 100px;">
                        <i class="fa-solid fa-user fa-2x"></i>
                    </div>
                @endif
                <h6 class="fw-bold mb-1">{{ $pi->nama }}</h6>
                <p class="text-success small fw-semibold mb-2">{{ $pi->jabatan }}</p>
                <p class="text-muted small mb-0">{{ $pi->divisi }}</p>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-light text-center border">Belum ada data pengurus inti.</div></div>
    @endforelse
</div>

<!-- Departemen / Anggota Lainnya -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold text-success mb-3 border-bottom pb-2"><i class="fa-solid fa-network-wired me-2"></i>Departemen & Anggota</h4>
    </div>
    @forelse($departemen as $d)
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 text-center p-3 h-100">
                @if($d->foto)
                    <img src="{{ asset('storage/' . $d->foto) }}" class="rounded-circle mx-auto mb-3" alt="{{ $d->nama }}" style="width: 80px; height: 80px; object-fit: cover;">
                @else
                    <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center text-white" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-user"></i>
                    </div>
                @endif
                <h6 class="fw-bold mb-1">{{ $d->nama }}</h6>
                <p class="text-success small fw-semibold mb-1">{{ $d->jabatan }}</p>
                <span class="badge bg-secondary mb-0">{{ $d->divisi }}</span>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-light text-center border">Belum ada data departemen.</div></div>
    @endforelse
</div>
@endsection