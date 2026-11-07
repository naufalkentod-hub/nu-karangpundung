@extends('frontend.layouts.app')

@section('title', 'Formulir Infaq')

@section('content')
<div class="row justify-content-center py-3 py-md-4">
    <div class="col-lg-8 col-xl-7">
        <a href="{{ route('infaq') }}" class="text-success text-decoration-none small fw-semibold">
            <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i>Kembali ke halaman infaq
        </a>
        <div class="mt-3 mb-4">
            <p class="text-success small fw-bold text-uppercase mb-1">Berbagi manfaat</p>
            <h1 class="h2 fw-bold">Formulir Infaq</h1>
            <p class="text-muted mb-0">Isi data untuk mengirim permohonan. Transaksi belum dianggap dibayar sampai dana diperiksa pengurus.</p>
        </div>

        @if(!$bank['configured'])
            <div class="alert alert-warning" role="alert">
                <i class="fa-solid fa-circle-info me-2" aria-hidden="true"></i>
                <strong>Instruksi pembayaran belum tersedia.</strong> Permohonan tetap dapat dikirim dan akan menunggu informasi resmi dari pengurus. Jangan transfer ke nomor yang tidak diumumkan oleh pengurus.
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <p class="fw-semibold mb-1">Periksa kembali isian berikut:</p>
                <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form id="infaq-form" action="{{ route('infaq.store') }}" method="POST" class="card border-0 shadow-sm rounded-4">
            @csrf
            <input type="hidden" name="idempotency_key" value="{{ $idempotencyKey }}">
            <div class="card-body p-4 p-md-5">
                <div class="mb-4">
                    <label for="donor_name" class="form-label fw-semibold">Nama donatur</label>
                    <input type="text" id="donor_name" name="donor_name" value="{{ old('donor_name') }}" class="form-control form-control-lg" maxlength="120" autocomplete="name">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous" value="1" @checked(old('is_anonymous'))>
                        <label class="form-check-label" for="is_anonymous">Saya ingin berdonasi secara anonim</label>
                    </div>
                    <div class="form-text">Nama tidak pernah ditampilkan pada riwayat publik.</div>
                </div>

                <fieldset class="mb-4">
                    <legend class="form-label fw-semibold">Pilih nominal infaq</legend>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach([10000, 25000, 50000, 100000] as $preset)
                            <button class="btn btn-outline-success amount-preset" type="button" data-amount="{{ $preset }}">Rp{{ number_format($preset, 0, ',', '.') }}</button>
                        @endforeach
                        <button class="btn btn-outline-secondary amount-preset" type="button" data-amount="">Nominal lainnya</button>
                    </div>
                    <label for="amount" class="form-label">Nominal (rupiah)</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">Rp</span>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" class="form-control" min="1" max="1000000000" step="1" required inputmode="numeric" aria-describedby="amount-help">
                    </div>
                    <div id="amount-help" class="form-text">Masukkan bilangan bulat lebih dari Rp0 (maksimal Rp1.000.000.000).</div>
                </fieldset>

                <div class="mb-4">
                    <label for="message" class="form-label fw-semibold">Pesan atau doa <span class="text-muted fw-normal">(opsional)</span></label>
                    <textarea id="message" name="message" class="form-control" rows="3" maxlength="1000">{{ old('message') }}</textarea>
                    <div class="form-text">Pesan ini hanya dapat dilihat oleh pengurus yang mengelola infaq.</div>
                </div>

                <div class="mb-4">
                    <label for="payment_method" class="form-label fw-semibold">Metode pembayaran</label>
                    <select id="payment_method" name="payment_method" class="form-select form-select-lg" required>
                        <option value="manual_transfer" @selected(old('payment_method', 'manual_transfer') === 'manual_transfer')>Transfer manual — instruksi dari pengurus</option>
                    </select>
                    <div class="form-text">
                        @if($bank['configured'])
                            Transfer mengikuti instruksi rekening resmi yang ditampilkan setelah permohonan disimpan.
                        @else
                            Pengurus akan memberikan instruksi pembayaran resmi setelah menerima permohonan ini.
                        @endif
                        QRIS dan payment gateway belum dikonfigurasi.
                    </div>
                </div>

                <div class="form-check p-3 rounded-3 mb-4" style="background: #f0f3e9;">
                    <input class="form-check-input ms-0 me-2" type="checkbox" id="consent" name="consent" value="1" required @checked(old('consent'))>
                    <label class="form-check-label" for="consent">Saya memahami bahwa dana dikumpulkan untuk kegiatan organisasi, sosial, pendidikan, dan kebutuhan lain yang disetujui pengurus.</label>
                </div>

                <button id="infaq-submit" type="submit" class="btn btn-success btn-lg w-100">
                    <span class="submit-label"><i class="fa-solid fa-hand-holding-heart me-2" aria-hidden="true"></i>Kirim Permohonan Infaq</span>
                    <span class="submit-loading d-none"><span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Menyimpan permohonan...</span>
                </button>
                <p class="small text-muted text-center mt-3 mb-0"><i class="fa-solid fa-shield-halved me-1" aria-hidden="true"></i>Jangan memasukkan kata sandi atau data kartu pembayaran.</p>
            </div>
        </form>
    </div>
</div>

<script>
    const infaqForm = document.getElementById('infaq-form');
    const amountInput = document.getElementById('amount');
    const donorNameInput = document.getElementById('donor_name');
    const anonymousInput = document.getElementById('is_anonymous');
    const submitButton = document.getElementById('infaq-submit');

    document.querySelectorAll('.amount-preset').forEach((button) => {
        button.addEventListener('click', () => {
            amountInput.value = button.dataset.amount;
            if (!button.dataset.amount) amountInput.focus();
        });
    });

    anonymousInput.addEventListener('change', () => {
        donorNameInput.disabled = anonymousInput.checked;
        donorNameInput.required = !anonymousInput.checked;
        if (anonymousInput.checked) donorNameInput.value = '';
    });
    anonymousInput.dispatchEvent(new Event('change'));

    infaqForm.addEventListener('submit', () => {
        submitButton.disabled = true;
        submitButton.querySelector('.submit-label').classList.add('d-none');
        submitButton.querySelector('.submit-loading').classList.remove('d-none');
    });
</script>
@endsection
