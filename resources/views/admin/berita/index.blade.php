@extends('admin.layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">Kelola Berita</h1>
    <p class="text-secondary mb-0">Tulis dan atur berita yang ditampilkan kepada pengunjung.</p>
</div>

<div class="card p-4 mb-4">
    <h2 class="h6 fw-bold mb-3">Tambah Berita</h2>
    @include('admin.berita._form', ['berita' => null, 'action' => route('admin.berita.store'), 'method' => 'POST'])
</div>

<div class="card p-4">
    <h2 class="h6 fw-bold mb-3">Daftar Berita</h2>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Gambar</th><th>Judul</th><th>Kategori</th><th>Publikasi</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse($beritas as $berita)
                    <tr>
                        <td>
                            @if($berita->gambar)
                                <img src="{{ asset('storage/'.$berita->gambar) }}" alt="Gambar {{ $berita->judul }}" class="admin-photo-thumbnail">
                            @else
                                <span class="text-secondary">-</span>
                            @endif
                        </td>
                        <td>{{ $berita->judul }}</td>
                        <td>{{ $berita->kategori }}</td>
                        <td>{{ $berita->tanggal_publikasi->format('d M Y') }}</td>
                        <td>{{ $berita->tanggal_publikasi->isFuture() ? 'Terjadwal' : 'Terbit' }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.berita.edit', $berita) }}" class="btn btn-sm btn-outline-success" aria-label="Edit {{ $berita->judul }}"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.berita.destroy', $berita) }}" class="d-inline" onsubmit="return confirm('Hapus berita ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Hapus {{ $berita->judul }}"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada berita.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $beritas->links() }}</div>
</div>
@endsection
