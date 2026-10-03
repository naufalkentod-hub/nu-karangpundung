@extends('frontend.layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center py-4">
        <h2 class="fw-bold text-success">Tentang IPNU Desa Karangpundung</h2>
        <p class="text-muted">Mengenal lebih dekat sejarah, visi, misi, dan nilai perjuangan pelajar Nahdlatul Ulama.</p>
        <hr class="w-25 mx-auto bg-success">
    </div>
</div>

<div class="row align-items-center mb-5">
    <div class="col-md-6 mb-4 mb-md-0">
        <div class="card shadow-sm border-0 bg-success text-white p-4">
            <h3 class="fw-bold mb-3"><i class="fa-solid fa-bullseye me-2"></i>Visi Kami</h3>
            <p class="fs-5 mb-0">Terwujudnya pelajar yang bertaqwa kepada Allah SWT, berilmu, berakhlak mulia, dan bertanggung jawab atas tegaknya syariat Islam berhaluan Ahlussunnah wal Jama'ah an-Nahdliyah.</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 bg-light p-4">
            <h3 class="fw-bold mb-3 text-success"><i class="fa-solid fa-list-check me-2"></i>Misi Kami</h3>
            <ul class="mb-0 ps-3">
                <li class="mb-2">Menyelenggarakan kaderisasi pelajar yang berkesinambungan dan berbasis kompetensi.</li>
                <li class="mb-2">Mengembangkan potensi keilmuan, sosial, dan kreativitas pemuda desa.</li>
                <li class="mb-2">Memperkuat amaliah keagamaan moderat di tengah masyarakat Karangpundung.</li>
                <li>Menjalin sinergi aktif dengan pemerintah desa dan lembaga kemasyarakatan lainnya.</li>
            </ul>
        </div>
    </div>
</div>

<div class="row bg-white p-5 rounded shadow-sm mb-4">
    <div class="col-12">
        <h3 class="fw-bold text-success mb-3"><i class="fa-solid fa-history me-2"></i>Sejarah Singkat & Perjuangan</h3>
        <p class="text-secondary lh-lg">
            Ikatan Pelajar Nahdlatul Ulama (IPNU) Desa Karangpundung hadir sebagai wadah perjuangan pelajar Islam dalam menjawab tantangan zaman. Berdiri atas dasar semangat kebersamaan para santri dan pemuda setempat, organisasi ini terus berkomitmen menjadi pelopor perubahan positif, penjaga tradisi keislaman yang rahmatan lil 'alamin, serta penggerak kegiatan sosial kemasyarakatan di tingkat desa.
        </p>
        <p class="text-secondary lh-lg mb-0">
            Melalui berbagai program kerja di bidang pendidikan, keagamaan, seni budaya, dan kewirausahaan, IPNU Karangpundung bertekad melahirkan generasi muda yang cerdas secara intelektual, matang secara spiritual, serta peduli terhadap kemajuan desa.
        </p>
    </div>
</div>
@endsection