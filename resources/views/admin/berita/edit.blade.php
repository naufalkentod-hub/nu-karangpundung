@extends('admin.layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">Edit Berita</h1>
    <p class="text-secondary mb-0">Perbarui isi berita “{{ $berita->judul }}”.</p>
</div>

<div class="card p-4">
    @include('admin.berita._form', ['action' => route('admin.berita.update', $berita), 'method' => 'PUT'])
</div>
@endsection
