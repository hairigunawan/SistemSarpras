<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan {{ $periode ?? 'Bulanan' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #2d3436;
            background: #ffffff;
            line-height: 1.4;
            font-size: 13px;
        }

        .container {
            width: 100%;
            padding: 10px 20px;
        }

        /* Hindari elemen pecah saat PDF render */
        .section, table, .ranking, header, footer, .stat-card {
            page-break-inside: avoid;
        }

        /* HEADER */
        header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e2e2;
        }

        h1 {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .app-name {
            font-size: 11px;
            color: #7f8c8d;
        }

        /* INFO PERIODE */
        .periode-info {
            background: #eef6ff;
            border-left: 4px solid #2980b9;
            padding: 10px 12px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .periode-info p {
            margin: 3px 0;
            font-size: 12px;
        }

        /* SECTION TITLE */
        .section-title {
            background: #2980b9;
            color: white;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        /* STAT CARD */
        .stats-grid {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .stat-card {
            flex: 1;
            min-width: 180px;
            background: #f7faff;
            padding: 12px;
            border-left: 4px solid #3498db;
            border-radius: 4px;
        }

        .stat-card.green {
            border-left-color: #27ae60;
        }

        .stat-card.purple {
            border-left-color: #8e44ad;
        }

        .stat-card-title {
            font-size: 11px;
            color: #7f8c8d;
            margin-bottom: 4px;
        }

        .stat-card-value {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table.summary th {
            background: #3498db;
            color: white;
            padding: 8px;
            font-size: 12px;
            text-align: left;
        }

        table.summary td {
            padding: 8px;
            border-bottom: 1px solid #e2e2e2;
        }

        table.summary tr:nth-child(even) {
            background: #f8fbff;
        }

        /* RANKING */
        .ranking {
            display: flex;
            align-items: center;
            padding: 12px 10px;
            border-bottom: 1px solid #e5e5e5;
        }

        /* Nomor ranking */
        .rank-number {
            width: 26px;
            height: 26px;
            background: #3498db;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            font-weight: bold;
            flex-shrink: 0;
            margin-right: 12px;
        }

        .rank-info {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .rank-name {
            font-size: 13px;
            font-weight: bold;
            color: #2c3e50;
            text-transform: capitalize;
        }

        .rank-detail {
            font-size: 11px;
            color: #7f8c8d;
        }

        /* Total peminjaman */
        .rank-count {
            font-size: 14px;
            font-weight: bold;
            color: #27ae60;
            text-align: right;
            white-space: nowrap;
        }

        .rank-count span {
            font-size: 10px;
            font-weight: normal;
            display: block;
        }

        /* FOOTER */
        footer {
            text-align: right;
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 20px;
            border-top: 1px solid #dcdcdc;
            padding-top: 6px;
        }

        @page {
            size: A4 portrait;
            margin: 10mm;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📊 LAPORAN PEMINJAMAN</h1>
            <p class="app-name">{{ config('app.name') }}</p>
        </header>

        <div class="periode-info">
            <p><strong>Periode:</strong> {{ $periode ?? '-' }}</p>
            <p><strong>Tanggal Cetak:</strong> {{ $tanggal }}</p>
        </div>

        <!-- STATISTIK -->
        <div class="section">
            <div class="section-title">📈 RINGKASAN STATISTIK</div>
            <div class="stats-grid">
                <div class="stat-card green">
                    <div class="stat-card-title">Total Peminjaman</div>
                    <div class="stat-card-value">{{ $totalPeminjaman ?? 0 }}</div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-card-title">Rata-Rata Jam Penggunaan</div>
                    <div class="stat-card-value">{{ $waktuRataRata ?? '0' }} jam</div>
                </div>
            </div>
        </div>

        <!-- RINGKASAN -->
        <div class="section">
            <div class="section-title">📋 RINGKASAN DATA</div>
            <table class="summary">
                <thead>
                    <tr>
                        <th>Keterangan</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Sarana Prasarana Terbanyak</strong></td>
                        <td>{{ $laporan->sarpras_terbanyak ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Ruangan Tersering Dipakai</strong></td>
                        <td>{{ $laporan->ruangan_tersering ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Rata-Rata Jam Penggunaan</strong></td>
                        <td>{{ $laporan->jam_selesai ?? '-' }} jam</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- TOP PEMINJAM -->
        @if(!empty($peminjamTeratas) && count($peminjamTeratas) > 0)
        <div class="section">
            <div class="section-title">👥 PEMINJAM TERATAS (TOP 3)</div>
            @foreach($peminjamTeratas as $index => $peminjam)
            <div class="ranking">
                <div class="rank-number">{{ $index + 1 }}</div>
                <div class="rank-info">
                    <div class="rank-name">{{ $peminjam['nama'] }}</div>
                    <div class="rank-detail">{{ $peminjam['email'] }}</div>
                </div>
                <div class="rank-count">
                    {{ $peminjam['jumlah'] }}
                    <span>peminjaman</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- TOP SARPRAS -->
        @if(!empty($sarprasTerpopuler) && count($sarprasTerpopuler) > 0)
        <div class="section">
            <div class="section-title">🏛️ SARANA PRASARANA TERPOPULER (TOP 3)</div>
            @foreach($sarprasTerpopuler as $index => $sarpras)
            <div class="ranking">
                <div class="rank-number">{{ $index + 1 }}</div>
                <div class="rank-info">
                    <div class="rank-name">{{ $sarpras['nama'] }}</div>
                    <div class="rank-detail">Sarana Prasarana</div>
                </div>
                <div class="rank-count">
                    {{ $sarpras['jumlah'] }}
                    <span>peminjaman</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- FOOTER -->
        <footer>
            <p>Laporan ini dicetak otomatis oleh sistem {{ config('app.name') }}</p>
            <p>{{ $tanggal }}</p>
        </footer>
    </div>
</body>
</html>