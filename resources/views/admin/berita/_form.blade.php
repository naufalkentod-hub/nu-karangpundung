@if($errors->any())
    <div class="alert alert-danger">
        <p class="fw-bold mb-1">Periksa kembali data berita:</p>
        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <div class="row g-3">
        <div class="col-md-8">
            <label for="judul" class="form-label">Judul berita</label>
            <input id="judul" name="judul" class="form-control" value="{{ old('judul', $berita?->judul) }}" maxlength="255" required>
        </div>
        <div class="col-md-4">
            <label for="kategori" class="form-label">Kategori</label>
            <input id="kategori" name="kategori" class="form-control" value="{{ old('kategori', $berita?->kategori) }}" maxlength="100" placeholder="Kegiatan" required>
        </div>
        <div class="col-md-6">
            <label for="tanggal_publikasi" class="form-label">Tanggal publikasi</label>
            <input id="tanggal_publikasi" name="tanggal_publikasi" type="date" class="form-control" value="{{ old('tanggal_publikasi', $berita?->tanggal_publikasi?->format('Y-m-d') ?? today()->format('Y-m-d')) }}" required>
        </div>
        <div class="col-md-6">
            <label for="gambar" class="form-label">Gambar berita (opsional)</label>
            <input id="gambar" name="gambar" type="file" accept="image/jpeg,image/png,image/webp" class="form-control">
            @if($berita?->gambar)
                <div class="mt-2"><img src="{{ asset('storage/'.$berita->gambar) }}" alt="Gambar berita saat ini" class="admin-photo-thumbnail"></div>
            @endif
        </div>
        <div class="col-12">
            <label for="ringkasan" class="form-label">Ringkasan</label>
            <textarea id="ringkasan" name="ringkasan" rows="3" maxlength="1000" class="form-control" required>{{ old('ringkasan', $berita?->ringkasan) }}</textarea>
        </div>
        <div class="col-12">
            <label for="isi" class="form-label">Isi berita</label>
            <textarea id="isi" name="isi" rows="10" class="form-control" required>{{ old('isi', $berita?->isi) }}</textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-success" type="submit">{{ $method === 'POST' ? 'Tambah Berita' : 'Simpan Perubahan' }}</button>
            @if($method !== 'POST')
                <a href="{{ route('admin.berita.index') }}" class="btn btn-outline-secondary">Batal</a>
            @endif
        </div>
    </div>
</form>
