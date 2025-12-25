<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        @page {
            margin: 2.5cm 2cm;
        }

        .w-full { width: 100%; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-sm { font-size: 10px; color: #64748b; }
        .font-bold { font-weight: bold; }
        .mb-4 { margin-bottom: 1rem; }
        .mt-4 { margin-top: 1rem; }

        .no-break { page-break-inside: avoid; }

        .header-table {
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 15px;
        }
        .company-name {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .report-title {
            font-size: 14px;
            color: #1e293b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .info-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }
        .info-value {
            font-size: 12px;
            color: #0f172a;
            font-weight: 500;
        }

        /* --- CARDS (Summary) --- */
        .card-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px 0; /* Gap antar card */
            /* REVISI: Hapus margin negatif agar tidak error di PDF */
            margin-bottom: 30px;
        }
        .card {
            background-color: #f8fafc; /* Slate 50 */
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .card-title {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .card-value {
            font-size: 22px;
            font-weight: 800;
            color: #1e293b;
        }

        /* --- MAIN TABLE STYLE --- */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .modern-table th {
            background-color: #f1f5f9; /* Slate 100 */
            color: #475569;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid #cbd5e1;
        }
        .modern-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            vertical-align: top;
        }
        /* Zebra Striping halus */
        .modern-table tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        /* --- BADGES --- */
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }
        .status-success { background-color: #dcfce7; color: #166534; }
        .status-pending { background-color: #fef9c3; color: #854d0e; }
        .status-danger { background-color: #fee2e2; color: #991b1b; }
        .status-neutral { background-color: #f1f5f9; color: #475569; }

        /* --- FOOTER --- */
        .footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            height: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 9px;
            color: #94a3b8;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="footer">
        Halaman <span class="pagenum"></span> | Dicetak: <?php echo e($tanggalCetak); ?> | <?php echo e(config('app.name')); ?>

    </div>

    <div class="w-full">

        <table class="header-table">
            <tr>
                <td width="60%" style="vertical-align: bottom;">
                    <div class="company-name">SIMPERSITE</div>

                </td>
                <td width="40%" style="text-align: right; vertical-align: bottom;">
                    <div style="font-size: 12px; font-weight: bold;"><?php echo e(config('app.name')); ?> System</div>
                    <div class="text-sm">Data Laporan Sarana & Prasarana</div>
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td width="33%">
                    <div class="info-label">Periode Laporan</div>
                    <div class="info-value"><?php echo e($periode ?? 'Semua Waktu'); ?></div>
                </td>
                <td width="33%">
                    <div class="info-label">User Pencetak</div>
                    <div class="info-value"><?php echo e(auth()->user()->nama); ?></div>
                </td>
                <td width="33%">
                    <div class="info-label">Total Record</div>
                    <div class="info-value"><?php echo e($totalPeminjaman ?? 0); ?> Data</div>
                </td>
            </tr>
        </table>

        <div class="no-break">
            <table class="card-table">
                <tr>
                    <td width="33%">
                        <div class="card">
                            <div class="card-title">Total Peminjaman</div>
                            <div class="card-value"><?php echo e($totalPeminjaman ?? 0); ?></div>
                        </div>
                    </td>
                    <td width="33%">
                        <div class="card">
                            <div class="card-title">Rata-rata Durasi</div>
                            <div class="card-value"><?php echo e($waktuRataRata ?? '0'); ?> <span style="font-size:12px; color:#64748b;">Jam</span></div>
                        </div>
                    </td>
                    <td width="33%">
                        <div class="card">
                            <div class="card-title">Item Terpopuler</div>
                            <div class="card-value" style="font-size: 14px; padding-top:6px;">
                                <?php echo e(\Illuminate\Support\Str::limit($laporan->sarpras_terbanyak ?? '-', 15)); ?>

                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-bottom: 10px;">
            <div style="font-size: 14px; font-weight: bold; color: #1e293b; margin-bottom: 10px; border-left: 4px solid #1e293b; padding-left: 10px;">
                Detail Transaksi Peminjaman
            </div>
            <table class="modern-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="18%">Tanggal</th>
                        <th width="25%">Sarana / Prasarana</th>
                        <th width="32%">Kegiatan</th>
                        <th width="20%" class="text-right">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $peminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td>
                            <div style="font-weight: bold;">
                                <?php echo e(\Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y')); ?>

                            </div>
                            <div class="text-sm">
                                <?php echo e(\Carbon\Carbon::parse($item->jam_mulai)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($item->jam_selesai)->format('H:i')); ?>

                            </div>
                        </td>
                        <td>
                            <?php if($item->ruangan): ?>
                                <div style="color: #1e293b; font-weight:500;"><?php echo e($item->ruangan->nama_ruangan); ?></div>
                                <div class="text-sm">Tipe: Ruangan</div>
                            <?php elseif($item->proyektor): ?>
                                <div style="color: #1e293b; font-weight:500;"><?php echo e($item->proyektor->nama_proyektor); ?></div>
                                <div class="text-sm">Tipe: Barang</div>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="line-height: 1.3;"><?php echo e($item->jenis_kegiatan); ?></div>
                        </td>
                        <td class="text-right">
                            <?php
                                $status = $item->statusPeminjaman->nama_status ?? $item->status_peminjaman ?? '-';
                                if(stripos($status, 'Setuju') !== false || stripos($status, 'Selesai') !== false);
                                elseif(stripos($status, 'Tolak') !== false);
                                elseif(stripos($status, 'Tunggu') !== false);
                            ?>
                            <span class="status-badge"><?php echo e($status); ?></span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 30px;">
                            <div style="font-style: italic; color: #94a3b8;">Tidak ada data ditemukan untuk periode ini.</div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if((!empty($peminjamTeratas) && count($peminjamTeratas) > 0) || (!empty($sarprasTerpopuler) && count($sarprasTerpopuler) > 0)): ?>
        <div class="no-break" style="page-break-inside: avoid;">
            <table style="width: 100%;">
                <tr>
                    <td width="50%" style="vertical-align: top; padding-right: 10px;">
                        <div style="font-size: 12px; font-weight: bold; margin-bottom: 8px;">
                            TOP Peminjam Teraktif
                        </div>
                        <table class="modern-table" style="margin-bottom: 0;">
                            <tbody>
                                <?php $__currentLoopData = $peminjamTeratas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td width="10%" style="padding: 8px;">
                                        <div style="font-size: 10px; font-weight: bold;"><?php echo e($idx + 1); ?></div>
                                    </td>
                                    <td style="padding: 8px;">
                                        <div style="font-weight: 600; font-size: 10px;"><?php echo e($p['nama']); ?></div>
                                        <div style="font-size: 9px; color: #94a3b8;"><?php echo e($p['email']); ?></div>
                                    </td>
                                    <td style="padding: 8px; text-align: right; font-weight: bold; color: #1e293b;">
                                        <?php echo e($p['jumlah']); ?>x
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </td>

                    <td width="50%" style="vertical-align: top; padding-left: 10px;">
                        <div style="font-size: 12px; font-weight: bold; margin-bottom: 8px;">
                            TOP Sarpras Favorit
                        </div>
                        <table class="modern-table" style="margin-bottom: 0;">
                            <tbody>
                                <?php $__currentLoopData = $sarprasTerpopuler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td width="10%" style="padding: 8px;">
                                        <div style="font-size: 10px; font-weight: bold;"><?php echo e($idx + 1); ?></div>
                                    </td>
                                    <td style="padding: 8px;">
                                        <div style="font-weight: 600; font-size: 10px;"><?php echo e($s['nama']); ?></div>
                                        <div style="font-size: 9px; color: #94a3b8;"><?php echo e($s['lokasi'] ?? $s['merk']); ?></div>
                                    </td>
                                    <td style="padding: 8px; text-align: right; font-weight: bold; color: #1e293b;">
                                        <?php echo e($s['jumlah']); ?>x
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

    </div>
</body>
</html>
<?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/laporan/pdf.blade.php ENDPATH**/ ?>