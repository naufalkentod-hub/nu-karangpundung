@extends('admin.layouts.app')

@section('content')
<!-- KARTU STATISTIK DINAMIS -->
<div class="row g-3 mb-4 dashboard-stats">
    <div class="col-xl-3 col-md-6">
        <div class="card p-3 border-start border-success border-4">
            <div class="d-flex align-items-center">
                <div class="bg-success text-white p-3 rounded-3 me-3 fs-4">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-bold">Total Anggota</p>
                    <h3 class="fw-bold mb-0">{{ $totalAnggota ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card p-3 border-start border-primary border-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary text-white p-3 rounded-3 me-3 fs-4">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-bold">Total Agenda</p>
                    <h3 class="fw-bold mb-0">{{ $totalAgenda ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card p-3 border-start border-warning border-4">
            <div class="d-flex align-items-center">
                <div class="bg-warning text-white p-3 rounded-3 me-3 fs-4">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-bold">Wisata Desa</p>
                    <h3 class="fw-bold mb-0">{{ $totalWisata ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card p-3 border-start border-danger border-4">
            <div class="d-flex align-items-center">
                <div class="bg-danger text-white p-3 rounded-3 me-3 fs-4">
                    <i class="fa-solid fa-inbox"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-bold">Kotak Saran</p>
                    <h3 class="fw-bold mb-0">{{ $totalSaran ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AKSI CEPAT & GRAFIK -->
<div class="row g-4 mb-4 dashboard-panels">
    <!-- GRAFIK AKTIVITAS -->
    <div class="col-lg-8">
        <div class="card p-4 h-100">
            <h5 class="fw-bold text-success mb-3"><i class="fa-solid fa-chart-line me-2"></i> Grafik Statistik Portal</h5>
            <div style="height: 280px;">
                <canvas id="statChart" data-values='@json($chartDataValues)'></canvas>
            </div>
        </div>
    </div>

    <!-- AKSI CEPAT -->
    <div class="col-lg-4">
        <div class="card p-4 h-100">
            <h5 class="fw-bold text-success mb-3"><i class="fa-solid fa-bolt me-2"></i> Aksi Cepat</h5>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-outline-success text-start py-2 fw-semibold">
                    <i class="fa-solid fa-user-plus me-2"></i> Kelola Anggota
                </a>
                <a href="{{ route('admin.agenda.index') }}" class="btn btn-outline-success text-start py-2 fw-semibold">
                    <i class="fa-solid fa-calendar-plus me-2"></i> Kelola Agenda
                </a>
                <a href="{{ route('admin.berita.index') }}" class="btn btn-outline-success text-start py-2 fw-semibold">
                    <i class="fa-solid fa-newspaper me-2"></i> Kelola Berita
                </a>
                <a href="{{ route('admin.wisata.index') }}" class="btn btn-outline-success text-start py-2 fw-semibold">
                    <i class="fa-solid fa-map-pin me-2"></i> Kelola Wisata Desa
                </a>
                <a href="{{ route('admin.saran.index') }}" class="btn btn-outline-success text-start py-2 fw-semibold">
                    <i class="fa-solid fa-comments me-2"></i> Lihat Kotak Saran
                </a>
                <a href="{{ route('admin.infaq.index') }}" class="btn btn-outline-success text-start py-2 fw-semibold">
                    <i class="fa-solid fa-hand-holding-heart me-2"></i> Kelola Infaq
                </a>
            </div>
        </div>
    </div>
</div>

<!-- INISIALISASI CHART.JS -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartElement = document.getElementById('statChart');
        const chartDataValues = JSON.parse(chartElement.dataset.values);
        const ctx = chartElement.getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Anggota', 'Agenda', 'Wisata', 'Saran'],
                datasets: [{
                    label: 'Jumlah Data',
                    data: chartDataValues,
                    backgroundColor: [
                        'rgba(25, 135, 84, 0.8)',
                        'rgba(13, 110, 253, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
@endsection