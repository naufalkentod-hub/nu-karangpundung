@extends('admin.layouts.app')

@section('content')
<div class="mb-4"><h1 class="h4 fw-bold mb-1">Data Agenda</h1><p class="text-secondary mb-0">Kelola agenda kegiatan IPNU Karangpundung.</p></div>

<div class="card p-4 mb-4">
    <h2 class="h6 fw-bold mb-3">Tambah Agenda</h2>
    <form method="POST" action="{{ route('admin.agenda.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6"><label for="judul" class="form-label">Judul</label><input id="judul" name="judul" class="form-control" value="{{ old('judul') }}" required></div>
            <div class="col-md-3"><label for="tanggal" class="form-label">Tanggal</label><input id="tanggal" name="tanggal" type="date" class="form-control" value="{{ old('tanggal') }}" required></div>
            <div class="col-md-3"><label for="waktu" class="form-label">Waktu</label><input id="waktu" name="waktu" class="form-control" value="{{ old('waktu') }}" placeholder="08.00 WIB" required></div>
            <div class="col-md-6"><label for="lokasi" class="form-label">Lokasi</label><input id="lokasi" name="lokasi" class="form-control" value="{{ old('lokasi') }}" required></div>
            <div class="col-md-6"><label for="status" class="form-label">Status</label><select id="status" name="status" class="form-select" required><option value="Akan Datang">Akan Datang</option><option value="Sedang Berlangsung">Sedang Berlangsung</option><option value="Selesai">Selesai</option></select></div>
            <div class="col-12"><label for="deskripsi" class="form-label">Deskripsi</label><textarea id="deskripsi" name="deskripsi" rows="3" class="form-control" required>{{ old('deskripsi') }}</textarea></div>
            <div class="col-md-6"><label for="foto" class="form-label">Foto (opsional)</label><input id="foto" name="foto" type="file" accept="image/jpeg,image/png" class="form-control"></div>
            <div class="col-12"><button class="btn btn-success" type="submit"><i class="fa-solid fa-plus me-1"></i> Tambah Agenda</button></div>
        </div>
    </form>
</div>

<div class="card p-4">
    <h2 class="h6 fw-bold mb-3">Daftar Agenda</h2>
    <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Judul</th><th>Tanggal</th><th>Lokasi</th><th>Status</th><th class="text-end">Aksi</th></tr></thead><tbody>
        @forelse ($agendas as $agenda)
            <tr><td>{{ $agenda->judul }}</td><td>{{ $agenda->tanggal }}</td><td>{{ $agenda->lokasi }}</td><td>{{ $agenda->status }}</td><td class="text-end"><form method="POST" action="{{ route('admin.agenda.destroy', $agenda) }}" onsubmit="return confirm('Hapus agenda ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Hapus {{ $agenda->judul }}"><i class="fa-solid fa-trash"></i></button></form></td></tr>
        @empty
            <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada agenda.</td></tr>
        @endforelse
    </tbody></table></div>
    <div class="mt-3">{{ $agendas->links() }}</div>
</div>
@endsection