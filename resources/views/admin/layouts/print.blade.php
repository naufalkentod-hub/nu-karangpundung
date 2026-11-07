<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('print-title') - NU Karangpundung</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 24px;
            color: #17211b;
            background: #f3f5f4;
            font: 14px/1.45 Arial, sans-serif;
        }

        .print-sheet {
            width: min(100%, 920px);
            margin: 0 auto;
            padding: 32px;
            background: #fff;
        }

        .print-actions {
            display: flex;
            justify-content: space-between;
            width: min(100%, 920px);
            margin: 0 auto 16px;
        }

        .print-actions a,
        .print-actions button {
            padding: 9px 14px;
            color: #145c38;
            background: #fff;
            border: 1px solid #198754;
            border-radius: 4px;
            font: inherit;
            text-decoration: none;
            cursor: pointer;
        }

        .print-header {
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 2px solid #198754;
        }

        .print-organization {
            margin: 0 0 4px;
            color: #145c38;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .08em;
        }

        .print-header h1 { margin: 0; font-size: 22px; }
        .print-header p:last-child { margin: 5px 0 0; color: #526158; }

        .print-table {
            width: 100%;
            border-collapse: collapse;
        }

        .print-table th,
        .print-table td {
            padding: 7px 8px;
            border: 1px solid #68736c;
            text-align: left;
            vertical-align: middle;
        }

        .print-table th { background: #edf4ef; font-size: 12px; }
        .print-table tbody tr { break-inside: avoid; }

        .member-card-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
        }

        .member-card {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
            height: 72px;
            padding: 6px;
            border: 1px solid #89948d;
            border-radius: 4px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .member-card-photo,
        .member-card-no-photo {
            display: grid;
            flex: 0 0 42px;
            place-items: center;
            width: 42px;
            height: 56px;
        }

        .member-card-photo { object-fit: cover; }
        .member-card-no-photo { color: #526158; background: #edf1ee; font-size: 8px; text-align: center; }
        .member-card-info { min-width: 0; }
        .member-card-name { margin: 0 0 3px; font-size: 11px; font-weight: 700; line-height: 1.2; overflow-wrap: anywhere; }
        .member-card-detail { margin: 0; color: #39463e; font-size: 9px; line-height: 1.25; overflow-wrap: anywhere; }

        @media (max-width: 640px) {
            .member-card-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media print {
            @page { size: A4 portrait; margin: 12mm; }
            body { padding: 0; background: #fff; font-size: 11px; }
            .print-actions { display: none !important; }
            .print-sheet { width: 100%; margin: 0; padding: 0; }
            .print-header { margin-bottom: 12px; }
            .print-header h1 { font-size: 18px; }
            .print-table th,
            .print-table td { padding: 5px; }
            .print-table th { background: #edf4ef !important; print-color-adjust: exact; }
            .member-card-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 6px; }
            .member-card { height: 66px; gap: 6px; padding: 4px; }
            .member-card-photo,
            .member-card-no-photo { flex-basis: 36px; width: 36px; height: 50px; }
            .member-card-name { font-size: 9px; }
            .member-card-detail { font-size: 8px; }
        }
    </style>
</head>
<body>
    <nav class="print-actions" aria-label="Aksi pratinjau cetak">
        <a href="{{ route('admin.cetak.index') }}">Kembali</a>
        <button type="button" onclick="window.print()">Cetak</button>
    </nav>

    <main class="print-sheet">
        <header class="print-header">
            <p class="print-organization">NU KARANGPUNDUNG</p>
            <h1>@yield('print-title')</h1>
            <p>@yield('print-count')</p>
        </header>
        @yield('print-content')
    </main>
</body>
</html>