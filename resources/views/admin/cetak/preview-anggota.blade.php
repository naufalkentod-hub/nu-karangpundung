@extends('admin.layouts.print')

@section('print-title', 'Daftar Anggota')
@section('print-count', $data->count() . ' data anggota')

@section('print-content')
<div class="member-card-grid">
    @forelse ($data as $index => $anggota)
        <article class="member-card">
            @if ($anggota->foto)
                <img class="member-card-photo" src="{{ asset('storage/' . $anggota->foto) }}" alt="Foto {{ $anggota->nama }}">
            @else
                <span class="member-card-no-photo">Tidak ada foto</span>
            @endif
            <div class="member-card-info">
                <h2 class="member-card-name">{{ $anggota->nama }}</h2>
                <p class="member-card-detail">{{ $anggota->jabatan }} - {{ $anggota->divisi }}</p>
                <p class="member-card-detail">Periode {{ $anggota->periode }}</p>
            </div>
        </article>
    @empty
        <p class="member-card-empty">Belum ada data anggota.</p>
    @endforelse
</div>
@endsection