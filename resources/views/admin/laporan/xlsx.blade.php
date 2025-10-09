<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 30px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        .footer {
            text-align: right;
            margin-top: 30px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>Laporan Bulanan</h1>
    <p><strong>Tanggal Cetak:</strong> {{ $tanggal }}</p>

    <table>
        <tr>
            <th>Sarana Prasarana Terbanyak</th>
            <td>{{ $laporan->sarpras_terbanyak ?? '-' }}</td>
        </tr>
        <tr>
            <th>Ruangan Tersering Dipakai</th>
            <td>{{ $laporan->ruangan_tersering ?? '-' }}</td>
        </tr>
        <tr>
            <th>Rata-Rata Jam Penggunaan</th>
            <td>{{ $laporan->jam_selesai ?? '-' }} jam</td>
        </tr>
    </table>

    <div class="footer">
        <p>Dicetak otomatis oleh sistem — {{ config('app.name') }}</p>
    </div>
</body>
</html>
