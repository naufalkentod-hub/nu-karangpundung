@extends('frontend.layouts.app')

@section('title', 'Daftar Anggota')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center py-4">
        <h2 class="fw-bold text-success">Direktori Anggota</h2>
        <p class="text-muted">Daftar seluruh anggota resmi IPNU Desa Karangpundung.</p>
        <hr class="w-25 mx-auto bg-success">
    </div>
</div>

<!-- Form Pencarian & Filter -->
<div class="row mb-4">
    <div class="col-md-12">
        <form action="{{ route('anggota') }}" method="GET" class="card shadow-sm border-0 p-3 bg-light">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau jabatan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-5">
                    <select name="divisi" class="form-select">
                        <option value="">Semua Divisi / Departemen</option>
                        @foreach($divisis as $div)
                            <option value="{{ $div }}" {{ request('divisi') == $div ? 'selected' : '' }}>{{ $div }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100"><i class="fa-solid fa-search me-1"></i> Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Kartu Anggota -->
<div class="row mb-4">
    @forelse($anggotas as $a)
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 text-center p-3 h-100">
                @if($a->foto)
                    <img src="{{ asset('storage/' . $a->foto) }}" class="rounded-circle mx-auto mb-3" alt="{{ $a->nama }}" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center text-white" style="width: 100px; height: 100px;">
                        <i class="fa-solid fa-user fa-2x"></i>
                    </div>
                @endif
                <h5 class="fw-bold mb-1">{{ $a->nama }}</h5>
                <p class="text-success small fw-semibold mb-1">{{ $a->jabatan }}</p>
                <span class="badge bg-secondary mb-2">{{ $a->divisi }}</span>
                <p class="text-muted small mb-0 mt-auto">Periode: {{ $a->periode }}</p>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">Data anggota tidak ditemukan.</div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="row">
    <div class="col-12 d-flex justify-content-center">
        {{ $anggotas->links() }}
    </div>
</div>
@endsection