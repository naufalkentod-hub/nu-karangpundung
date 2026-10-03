@extends('admin.layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1">Data Anggota</h1>
        <p class="text-secondary mb-0">Kelola anggota IPNU Karangpundung.</p>
    </div>
    <form method="POST" action="{{ route('admin.cetak.preview') }}">
        @csrf
        <input type="hidden" name="type" value="anggota">
        <button type="submit" class="btn btn-outline-success"><i class="fa-solid fa-print me-1"></i> Cetak Anggota</button>
    </form>
</div>

<div class="card p-4 mb-4">
    <h2 class="h6 fw-bold mb-3">Tambah Anggota</h2>
    <form method="POST" action="{{ route('admin.anggota.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6"><label for="nama" class="form-label">Nama</label><input id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>@error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label for="jabatan" class="form-label">Jabatan</label><input id="jabatan" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}" required>@error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label for="divisi" class="form-label">Divisi</label><input id="divisi" name="divisi" class="form-control @error('divisi') is-invalid @enderror" value="{{ old('divisi') }}" required>@error('divisi')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label for="periode" class="form-label">Periode</label><input id="periode" name="periode" class="form-control @error('periode') is-invalid @enderror" value="{{ old('periode') }}" required>@error('periode')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label for="foto" class="form-label">Foto (opsional)</label><input id="foto" name="foto" type="file" accept="image/jpeg,image/png" class="form-control @error('foto') is-invalid @enderror">@error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-12"><button class="btn btn-success" type="submit"><i class="fa-solid fa-plus me-1"></i> Tambah Anggota</button></div>
        </div>
    </form>
</div>

<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h6 fw-bold mb-0">Daftar Anggota</h2><span class="text-secondary small">{{ $anggotas->total() }} data</span></div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Foto</th><th>Nama</th><th>Jabatan</th><th>Divisi</th><th>Periode</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse ($anggotas as $anggota)
                    <tr>
                        <td>
                            @if ($anggota->foto)
                                <img src="{{ asset('storage/' . $anggota->foto) }}" alt="Foto {{ $anggota->nama }}" class="admin-photo-thumbnail">
                            @else
                                <span class="text-secondary">-</span>
                            @endif
                        </td>
                        <td>{{ $anggota->nama }}</td><td>{{ $anggota->jabatan }}</td><td>{{ $anggota->divisi }}</td><td>{{ $anggota->periode }}</td>
                        <td class="text-end"><form method="POST" action="{{ route('admin.anggota.destroy', $anggota) }}" onsubmit="return confirm('Hapus data anggota ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Hapus {{ $anggota->nama }}"><i class="fa-solid fa-trash"></i></button></form></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada data anggota.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $anggotas->links() }}</div>
</div>
@endsection