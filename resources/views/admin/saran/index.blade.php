@extends('admin.layouts.app')

@section('content')
<div class="mb-4"><h1 class="h4 fw-bold mb-1">Kotak Saran</h1><p class="text-secondary mb-0">Saran dan masukan yang dikirim melalui website.</p></div>

<div class="card p-4">
    <h2 class="h6 fw-bold mb-3">Daftar Saran</h2>
    <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Pengirim</th><th>Email</th><th>Kategori</th><th>Isi</th><th>Tanggal</th><th class="text-end">Aksi</th></tr></thead><tbody>
        @forelse ($sarans as $saran)
            <tr><td>{{ $saran->nama }}</td><td>{{ $saran->email }}</td><td>{{ $saran->kategori }}</td><td class="text-break" style="min-width: 220px">{{ $saran->isi }}</td><td>{{ $saran->created_at->format('d/m/Y') }}</td><td class="text-end"><form method="POST" action="{{ route('admin.saran.destroy', $saran) }}" onsubmit="return confirm('Hapus saran ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Hapus saran dari {{ $saran->nama }}"><i class="fa-solid fa-trash"></i></button></form></td></tr>
        @empty
            <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada saran.</td></tr>
        @endforelse
    </tbody></table></div>
    <div class="mt-3">{{ $sarans->links() }}</div>
</div>
@endsection