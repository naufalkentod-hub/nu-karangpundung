@extends('admin.layouts.app')

@section('content')
<div class="mb-4"><h1 class="h4 fw-bold mb-1">Cetak Data</h1><p class="text-secondary mb-0">Pilih data yang ingin ditinjau dan dicetak.</p></div>

<div class="card p-4" style="max-width: 560px">
    <form method="POST" action="{{ route('admin.cetak.preview') }}">
        @csrf
        <label for="type" class="form-label fw-semibold">Jenis data</label>
        <select id="type" name="type" class="form-select mb-3" required><option value="anggota">Anggota</option><option value="agenda">Agenda</option></select>
        <button type="submit" class="btn btn-success"><i class="fa-solid fa-print me-1"></i> Tampilkan Pratinjau</button>
    </form>
</div>
@endsection