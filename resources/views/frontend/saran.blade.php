@extends('frontend.layouts.app')

@section('title', 'Kotak Saran & Aspirasi')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="text-center py-4">
            <h2 class="fw-bold text-success">Kotak Saran & Aspirasi</h2>
            <p class="text-muted">Sampaikan kritik, saran, atau masukan membangun untuk kemajuan IPNU Karangpundung.</p>
            <hr class="w-25 mx-auto bg-success">
        </div>

        <div class="card shadow-sm border-0 p-4">
            <form action="{{ route('saran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Email Aktif</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori Aspirasi</label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">Pilih Kategori...</option>
                        <option value="Saran Program Kerja">Saran Program Kerja</option>
                        <option value="Kritik & Evaluasi">Kritik & Evaluasi</option>
                        <option value="Pengaduan / Pertanyaan">Pengaduan / Pertanyaan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Isi Pesan / Saran</label>
                    <textarea name="isi" rows="5" class="form-control @error('isi') is-invalid @enderror" required>{{ old('isi') }}</textarea>
                    @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Lampiran Foto (Opsional)</label>
                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror">
                    @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 fw-bold"><i class="fa-solid fa-paper-plane me-2"></i>Kirim Saran</button>
            </form>
        </div>
    </div>
</div>
@endsection