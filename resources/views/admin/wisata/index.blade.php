@extends('admin.layouts.app')

@section('content')
<div class="mb-4"><h1 class="h4 fw-bold mb-1">Wisata Desa</h1><p class="text-secondary mb-0">Kelola informasi destinasi wisata desa.</p></div>

<div class="card p-4 mb-4">
    <h2 class="h6 fw-bold mb-3">Tambah Destinasi</h2>
    <form method="POST" action="{{ route('admin.wisata.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6"><label for="nama" class="form-label">Nama Destinasi</label><input id="nama" name="nama" class="form-control" value="{{ old('nama') }}" required></div>
            <div class="col-md-6"><label for="lokasi" class="form-label">Lokasi</label><input id="lokasi" name="lokasi" class="form-control" value="{{ old('lokasi') }}" required></div>
            <div class="col-md-6"><label for="fasilitas" class="form-label">Fasilitas</label><input id="fasilitas" name="fasilitas" class="form-control" value="{{ old('fasilitas') }}" required></div>
            <div class="col-md-6"><label for="jam_buka" class="form-label">Jam Buka</label><input id="jam_buka" name="jam_buka" class="form-control" value="{{ old('jam_buka') }}" placeholder="08.00-17.00 WIB" required></div>
            <div class="col-12"><label for="deskripsi" class="form-label">Deskripsi</label><textarea id="deskripsi" name="deskripsi" rows="3" class="form-control" required>{{ old('deskripsi') }}</textarea></div>
            <div class="col-md-6"><label for="foto" class="form-label">Foto (opsional)</label><input id="foto" name="foto" type="file" accept="image/jpeg,image/png" class="form-control"></div>
            <div class="col-12"><button class="btn btn-success" type="submit"><i class="fa-solid fa-plus me-1"></i> Tambah Destinasi</button></div>
        </div>
    </form>
</div>

<div class="card p-4">
    <h2 class="h6 fw-bold mb-3">Daftar Destinasi</h2>
    <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Foto</th><th>Nama</th><th>Lokasi</th><th>Fasilitas</th><th>Jam Buka</th><th class="text-end">Aksi</th></tr></thead><tbody>
        @forelse ($wisatas as $wisata)
            <tr>
                <td>
                    @if ($wisata->foto)
                        <img src="{{ asset('storage/' . $wisata->foto) }}" alt="Foto {{ $wisata->nama }}" class="admin-photo-thumbnail">
                    @else
                        <span class="text-secondary">-</span>
                    @endif
                </td>
                <td>{{ $wisata->nama }}</td><td>{{ $wisata->lokasi }}</td><td>{{ $wisata->fasilitas }}</td><td>{{ $wisata->jam_buka }}</td><td class="text-end"><form method="POST" action="{{ route('admin.wisata.destroy', $wisata) }}" onsubmit="return confirm('Hapus destinasi ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Hapus {{ $wisata->nama }}"><i class="fa-solid fa-trash"></i></button></form></td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada destinasi wisata.</td></tr>
        @endforelse
    </tbody></table></div>
    <div class="mt-3">{{ $wisatas->links() }}</div>
</div>
@endsection