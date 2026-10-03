@extends('admin.layouts.print')

@section('print-title', 'Daftar Agenda')
@section('print-count', $data->count() . ' data agenda')

@section('print-content')
<table class="print-table">
    <thead><tr><th>No.</th><th>Judul</th><th>Tanggal</th><th>Waktu</th><th>Lokasi</th><th>Status</th></tr></thead>
    <tbody>
    @forelse ($data as $index => $agenda)
        <tr><td>{{ $index + 1 }}</td><td>{{ $agenda->judul }}</td><td>{{ $agenda->tanggal }}</td><td>{{ $agenda->waktu }}</td><td>{{ $agenda->lokasi }}</td><td>{{ $agenda->status }}</td></tr>
    @empty
        <tr><td colspan="6">Belum ada agenda.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection