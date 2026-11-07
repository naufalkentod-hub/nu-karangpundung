<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NU Karangpundung</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --ipnu-primary: #198754;
            --ipnu-dark: #145c38;
            --ipnu-light: #e8f5e9;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7f6;
            color: #334155;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            z-index: 1050;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            color: var(--ipnu-primary);
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-brand-logo {
            display: block;
            flex: 0 0 40px;
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .sidebar-brand-name {
            display: block;
            font-size: 0.95rem;
            font-weight: 800;
            line-height: 1.15;
        }

        .sidebar-brand-caption {
            display: block;
            margin-top: 3px;
            color: #64748b;
            font-size: 0.65rem;
            font-weight: 600;
        }

        .sidebar-nav {
            padding: 1rem 0.75rem;
            list-style: none;
            margin: 0;
        }

        .sidebar-nav .nav-item {
            margin-bottom: 0.25rem;
            animation: admin-slide-in 520ms cubic-bezier(.2, .7, .25, 1) both;
        }

        .sidebar-nav .nav-item:nth-child(1) { animation-delay: 80ms; }
        .sidebar-nav .nav-item:nth-child(2) { animation-delay: 140ms; }
        .sidebar-nav .nav-item:nth-child(3) { animation-delay: 200ms; }
        .sidebar-nav .nav-item:nth-child(4) { animation-delay: 260ms; }
        .sidebar-nav .nav-item:nth-child(5) { animation-delay: 320ms; }
        .sidebar-nav .nav-item:nth-child(6) { animation-delay: 380ms; }
        .sidebar-nav .nav-item:nth-child(7) { animation-delay: 440ms; }

        @keyframes admin-slide-in {
            from { opacity: 0; transform: translateX(-16px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes admin-fade-in-up {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dashboard-stats > [class*="col-"] {
            animation: admin-fade-in-up 620ms cubic-bezier(.2, .7, .25, 1) both;
        }

        .dashboard-stats > [class*="col-"]:nth-child(2) { animation-delay: 70ms; }
        .dashboard-stats > [class*="col-"]:nth-child(3) { animation-delay: 140ms; }
        .dashboard-stats > [class*="col-"]:nth-child(4) { animation-delay: 210ms; }
        .dashboard-panels { animation: admin-fade-in-up 650ms 180ms cubic-bezier(.2, .7, .25, 1) both; }

        @media (prefers-reduced-motion: reduce) {
            .sidebar-nav .nav-item,
            .dashboard-stats > [class*="col-"],
            .dashboard-panels {
                animation: none;
            }
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #64748b;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .sidebar-nav .nav-link:hover, 
        .sidebar-nav .nav-link.active {
            color: var(--ipnu-primary);
            background-color: var(--ipnu-light);
        }

        /* Main Content Wrapper */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Topbar Styling */
        .admin-topbar {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1040;
        }

        /* Cards & Shadows */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(25, 135, 84, 0.08);
        }

        .admin-photo-thumbnail {
            width: 52px;
            height: 64px;
            object-fit: cover;
            border-radius: 4px;
        }

        @media (max-width: 991.98px) {
            #sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }
            #sidebar.show {
                margin-left: 0;
            }
            #main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <nav id="sidebar">
        <div class="sidebar-brand">
            <img class="sidebar-brand-logo" src="{{ asset('images/logo-nu.png') }}" alt="Lambang Nahdlatul Ulama" width="40" height="40">
            <span><span class="sidebar-brand-name">NU Karangpundung</span><span class="sidebar-brand-caption">Nahdlatul Ulama</span></span>
        </div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie fa-fw"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.anggota.index') }}" class="nav-link {{ request()->routeIs('admin.anggota*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users fa-fw"></i> Anggota
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.agenda.index') }}" class="nav-link {{ request()->routeIs('admin.agenda*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days fa-fw"></i> Agenda
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.berita.index') }}" class="nav-link {{ request()->routeIs('admin.berita*') ? 'active' : '' }}">
                    <i class="fa-solid fa-newspaper fa-fw"></i> Berita
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.wisata.index') }}" class="nav-link {{ request()->routeIs('admin.wisata*') ? 'active' : '' }}">
                    <i class="fa-solid fa-map-location-dot fa-fw"></i> Wisata Desa
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.saran.index') }}" class="nav-link {{ request()->routeIs('admin.saran*') ? 'active' : '' }}">
                    <i class="fa-solid fa-inbox fa-fw"></i> Kotak Saran
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.cetak.index') }}" class="nav-link {{ request()->routeIs('admin.cetak*') ? 'active' : '' }}">
                    <i class="fa-solid fa-print fa-fw"></i> Cetak Data
                </a>
            </li>
            <li class="nav-item mt-4">
                <a href="{{ url('/') }}" target="_blank" class="nav-link text-secondary">
                    <i class="fa-solid fa-globe fa-fw"></i> Lihat Website
                </a>
            </li>
        </ul>
    </nav>

    <!-- MAIN CONTENT -->
    <div id="main-content">
        <!-- TOPBAR -->
        <header class="admin-topbar">
            <button class="btn btn-outline-success d-lg-none border-0" id="sidebarToggle">
                <i class="fa-solid fa-bars fa-lg"></i>
            </button>
            <h5 class="fw-bold m-0 text-dark">Panel Kontrol Admin</h5>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 38px; height: 38px;">
                        A
                    </div>
                    <span class="fw-semibold d-none d-md-inline">Administrator</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><a class="dropdown-item" href="{{ url('/') }}"><i class="fa-solid fa-home me-2"></i> Beranda Utama</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <!-- PAGE CONTENT CONTAINER -->
        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="div">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar Mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
</body>
</html>