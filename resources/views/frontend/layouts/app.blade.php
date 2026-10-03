<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IPNU Karangpundung') | IPNU Karangpundung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --ipnu-forest: #154734;
            --ipnu-green: #237b53;
            --ipnu-mint: #dcebd8;
            --ipnu-lime: #d8e88d;
            --ipnu-amber: #e6a151;
            --ipnu-ink: #1e2b24;
            --ipnu-muted: #68766d;
            --ipnu-paper: #f6f7f0;
        }

        body {
            color: var(--ipnu-ink);
            background: var(--ipnu-paper);
            font-family: 'DM Sans', sans-serif;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, .navbar-brand { font-family: 'Manrope', sans-serif; }

        .public-header {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: rgba(250, 251, 246, .96);
            border-bottom: 1px solid #e4e9df;
            backdrop-filter: blur(12px);
        }

        .public-nav { min-height: 74px; }
        .public-brand { display: flex; align-items: center; gap: 10px; color: var(--ipnu-forest); }
        .public-brand:hover { color: var(--ipnu-green); }
        .brand-mark { display: grid; place-items: center; width: 42px; height: 42px; color: #fff; background: var(--ipnu-forest); border-radius: 12px 12px 12px 3px; }
        .brand-name { display: block; font-size: 15px; font-weight: 800; line-height: 1.15; }
        .brand-caption { display: block; margin-top: 3px; color: var(--ipnu-muted); font-family: 'DM Sans', sans-serif; font-size: 10px; font-weight: 600; letter-spacing: .04em; }
        .public-nav .navbar-nav { gap: 4px; }
        .public-nav .nav-link { position: relative; padding: 10px 11px !important; color: #49594e; font-size: 13px; font-weight: 600; }
        .public-nav .nav-link::after { position: absolute; right: 11px; bottom: 4px; left: 11px; height: 2px; background: var(--ipnu-green); content: ''; transform: scaleX(0); transform-origin: left; transition: transform 180ms ease; }
        .public-nav .nav-link:hover,
        .public-nav .nav-link.active { color: var(--ipnu-forest); }
        .public-nav .nav-link:hover::after,
        .public-nav .nav-link.active::after { transform: scaleX(1); }
        .public-nav .navbar-toggler { border-color: #cbd6c9; }
        .public-nav .navbar-toggler:focus { box-shadow: 0 0 0 3px rgba(35, 123, 83, .16); }

        .public-main { min-height: calc(100vh - 190px); }
        .public-main:not(.home-main) { max-width: 1240px; margin: 0 auto; padding: 28px 20px 64px; }

        .home-hero { position: relative; display: flex; min-height: 590px; align-items: center; overflow: hidden; isolation: isolate; color: #fff; background: var(--ipnu-forest); }
        .home-hero-photo { position: absolute; z-index: -2; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center 53%; }
        .home-hero::after { position: absolute; z-index: -1; inset: 0; background: linear-gradient(90deg, rgba(11, 37, 26, .86) 0%, rgba(16, 52, 36, .68) 48%, rgba(17, 48, 35, .12) 100%); content: ''; }
        .home-hero-inner { width: min(100% - 40px, 1180px); margin: 0 auto; padding: 86px 0 104px; }
        .hero-copy { max-width: 620px; }
        .hero-kicker { display: inline-flex; align-items: center; gap: 9px; margin-bottom: 22px; color: var(--ipnu-lime); font-size: 12px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .hero-kicker::before { width: 24px; height: 2px; background: var(--ipnu-lime); content: ''; }
        .hero-title { max-width: 590px; margin: 0; font-size: 56px; font-weight: 800; letter-spacing: 0; line-height: 1.04; }
        .hero-description { max-width: 550px; margin: 22px 0 28px; color: rgba(255, 255, 255, .86); font-size: 17px; }
        .hero-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; }
        .hero-primary { padding: 12px 18px; color: var(--ipnu-forest); background: var(--ipnu-lime); border: 1px solid var(--ipnu-lime); border-radius: 4px; font-weight: 700; transition: transform 180ms ease, background 180ms ease; }
        .hero-primary:hover { color: var(--ipnu-forest); background: #e5f2ac; border-color: #e5f2ac; transform: translateY(-2px); }
        .hero-secondary { padding: 12px 14px; color: #fff; font-weight: 700; text-decoration: none; }
        .hero-secondary:hover { color: var(--ipnu-lime); }
        .hero-caption { position: absolute; right: 28px; bottom: 22px; color: rgba(255, 255, 255, .8); font-size: 10px; letter-spacing: .08em; text-transform: uppercase; }

        .home-stats { position: relative; z-index: 1; width: min(100% - 40px, 1180px); margin: -38px auto 0; padding: 0; background: #fff; border: 1px solid #e4e9df; border-radius: 6px; box-shadow: 0 16px 34px rgba(22, 48, 33, .08); }
        .stat-item { display: flex; min-height: 112px; align-items: center; gap: 14px; padding: 20px 24px; }
        .stat-icon { display: grid; flex: 0 0 42px; place-items: center; width: 42px; height: 42px; color: var(--ipnu-forest); background: var(--ipnu-mint); border-radius: 50%; font-size: 16px; }
        .stat-number { margin: 0; color: var(--ipnu-forest); font-size: 27px; font-weight: 800; line-height: 1; }
        .stat-label { margin: 6px 0 0; color: var(--ipnu-muted); font-size: 12px; }

        .home-section { width: min(100% - 40px, 1180px); margin: 0 auto; padding: 72px 0 8px; }
        .home-section > .row { margin-inline: 0; }
        .section-heading { display: flex; flex-wrap: wrap; align-items: end; justify-content: space-between; gap: 18px; margin-bottom: 24px; }
        .section-kicker { margin: 0 0 7px; color: var(--ipnu-green); font-size: 11px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .section-title { margin: 0; color: var(--ipnu-forest); font-size: 30px; font-weight: 800; letter-spacing: 0; }
        .section-link { color: var(--ipnu-green); font-size: 13px; font-weight: 700; text-decoration: none; }
        .section-link:hover { color: var(--ipnu-forest); }
        .feature-card { height: 100%; overflow: hidden; background: #fff; border: 1px solid #e4e9df; border-radius: 6px; transition: transform 200ms ease, box-shadow 200ms ease; }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 14px 28px rgba(22, 48, 33, .1); }
        .feature-image { display: block; width: 100%; height: 210px; object-fit: cover; }
        .feature-placeholder { display: grid; height: 210px; place-items: center; color: #6c806f; background: var(--ipnu-mint); font-size: 32px; }
        .feature-body { padding: 19px; }
        .feature-meta { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 10px; color: var(--ipnu-muted); font-size: 11px; }
        .feature-status { color: var(--ipnu-green); font-weight: 700; }
        .feature-title { margin: 0 0 9px; color: var(--ipnu-ink); font-size: 18px; font-weight: 800; }
        .feature-text { margin: 0; color: var(--ipnu-muted); font-size: 13px; }
        .feature-footer { padding: 0 19px 19px; }
        .feature-button { display: inline-flex; align-items: center; gap: 8px; color: var(--ipnu-green); font-size: 12px; font-weight: 700; text-decoration: none; }
        .feature-button:hover { color: var(--ipnu-forest); }
        .empty-state { padding: 24px; color: var(--ipnu-muted); background: #fff; border: 1px dashed #cbd6c9; border-radius: 6px; text-align: center; }

        .site-footer { margin-top: 72px; padding: 38px 0; color: rgba(255, 255, 255, .78); background: #123c2b; }
        .footer-inner { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px; width: min(100% - 40px, 1180px); margin: 0 auto; }
        .footer-brand { margin: 0; color: #fff; font-family: 'Manrope', sans-serif; font-size: 15px; font-weight: 800; }
        .footer-copy { margin: 5px 0 0; font-size: 12px; }
        .footer-link { color: var(--ipnu-lime); font-size: 12px; font-weight: 700; text-decoration: none; }
        .footer-link:hover { color: #fff; }

        @media (max-width: 991.98px) {
            .public-nav .navbar-collapse { padding: 10px 0 16px; }
            .public-nav .nav-link { padding: 10px 0 !important; }
            .public-nav .nav-link::after { right: auto; bottom: 4px; left: 0; width: 34px; }
            #publicNavigation:not(.show) { display: none; }
            #publicNavigation.show { display: block; }
            .home-hero { min-height: 540px; }
            .hero-title { font-size: 48px; }
            .home-stats { margin-top: -28px; }
            .stat-item { min-height: 96px; padding: 16px; }
        }

        @media (max-width: 575.98px) {
            .public-nav { min-height: 66px; }
            .home-hero { min-height: 560px; align-items: end; }
            .home-hero-photo { object-position: 58% center; }
            .home-hero::after { background: linear-gradient(0deg, rgba(9, 34, 24, .9) 0%, rgba(12, 39, 28, .66) 58%, rgba(17, 48, 35, .12) 100%); }
            .home-hero-inner { width: min(100% - 36px, 1180px); padding: 64px 0 86px; }
            .hero-title { font-size: 42px; }
            .hero-description { font-size: 15px; }
            .hero-caption { right: 18px; bottom: 14px; font-size: 9px; }
            .home-stats { width: min(100% - 28px, 1180px); margin-top: -20px; }
            .stat-item { min-height: 86px; gap: 9px; padding: 12px 10px; }
            .stat-icon { flex-basis: 34px; width: 34px; height: 34px; font-size: 13px; }
            .stat-number { font-size: 22px; }
            .stat-label { font-size: 10px; }
            .home-section { width: min(100% - 32px, 1180px); padding-top: 56px; }
            .section-title { font-size: 25px; }
            .feature-image, .feature-placeholder { height: 190px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; animation-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <header class="public-header">
        <nav class="navbar navbar-expand-lg public-nav container" aria-label="Navigasi utama">
            <a href="{{ route('beranda') }}" class="navbar-brand public-brand">
                <span class="brand-mark"><i class="fa-solid fa-seedling" aria-hidden="true"></i></span>
                <span><span class="brand-name">IPNU Karangpundung</span><span class="brand-caption">Ikatan Pelajar Nahdlatul Ulama</span></span>
            </a>
            <button id="publicNavToggle" class="navbar-toggler" type="button" aria-controls="publicNavigation" aria-expanded="false" aria-label="Buka navigasi">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
            <div class="collapse navbar-collapse" id="publicNavigation">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}" href="{{ route('beranda') }}">Beranda</a>
                    <a class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}" href="{{ route('tentang') }}">Tentang</a>
                    <a class="nav-link {{ request()->routeIs('struktur') ? 'active' : '' }}" href="{{ route('struktur') }}">Struktur</a>
                    <a class="nav-link {{ request()->routeIs('anggota') ? 'active' : '' }}" href="{{ route('anggota') }}">Anggota</a>
                    <a class="nav-link {{ request()->routeIs('agenda*') ? 'active' : '' }}" href="{{ route('agenda') }}">Agenda</a>
                    <a class="nav-link {{ request()->routeIs('wisata*') ? 'active' : '' }}" href="{{ route('wisata') }}">Wisata Desa</a>
                    <a class="nav-link {{ request()->routeIs('saran') ? 'active' : '' }}" href="{{ route('saran') }}">Kotak Saran</a>
                </div>
            </div>
        </nav>
    </header>

    <main id="main-content" class="public-main {{ request()->routeIs('beranda') ? 'home-main' : '' }}">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="footer-inner">
            <div>
                <p class="footer-brand">IPNU Karangpundung</p>
                <p class="footer-copy">Ruang tumbuh, belajar, dan berjuang bersama.</p>
            </div>
            <a class="footer-link" href="{{ route('login') }}">Masuk Admin <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></a>
        </div>
    </footer>

    <script>
        const publicNavToggle = document.getElementById('publicNavToggle');
        const publicNavigation = document.getElementById('publicNavigation');

        publicNavToggle?.addEventListener('click', function () {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', String(!isExpanded));
            publicNavigation.classList.toggle('show', !isExpanded);
        });

        publicNavigation?.addEventListener('click', function (event) {
            if (event.target.closest('.nav-link')) {
                publicNavToggle.setAttribute('aria-expanded', 'false');
                this.classList.remove('show');
            }
        });
    </script>
</body>
</html>