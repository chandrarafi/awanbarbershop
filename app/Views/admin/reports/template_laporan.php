<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Cetak Laporan' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
            color: #333;
            background-color: #fff;
        }

        .container-fluid {
            position: relative;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.03;
            z-index: -1;
            width: 500px;
            height: auto;
            pointer-events: none;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #A27B5C;
        }

        .logo-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo {
            width: 80px;
            height: auto;
            margin-right: 15px;
        }

        .header-text {
            text-align: center;
        }

        .header h2 {
            margin-bottom: 5px;
            color: #A27B5C;
            font-weight: 700;
            font-size: 24px;
        }

        .header h3 {
            margin-bottom: 0;
            font-size: 18px;
            font-weight: 600;
            color: #555;
        }

        .header p {
            margin-bottom: 0;
            font-size: 12px;
            color: #777;
        }

        .report-title {
            text-align: center;
            margin: 20px 0;
            font-size: 22px;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 10px;
            position: relative;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
        }

        .report-title:after {
            content: '';
            position: absolute;
            width: 70%;
            height: 2px;
            background-color: #A27B5C;
            bottom: 0;
            left: 15%;
        }

        .report-date {
            text-align: right;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
            text-align: center;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        .signature {
            width: 200px;
            text-align: center;
        }

        .signature p {
            margin-bottom: 50px;
        }

        .signature .name {
            font-weight: 600;
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 5px;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none;
            }

            @page {
                margin: 1.5cm;
            }
        }

        .btn-print {
            background-color: #A27B5C;
            border-color: #A27B5C;
            color: white;
        }

        .btn-print:hover {
            background-color: #8a6a4e;
            border-color: #8a6a4e;
            color: white;
        }

        .btn-close {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-close:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            color: white;
        }

        /* Tambahan style untuk filter dan pencarian */
        .filter-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .summary-section {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .summary-box {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .summary-box h4 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
            color: #A27B5C;
        }

        .summary-box .value {
            font-size: 20px;
            font-weight: 700;
            color: #333;
        }

        .summary-box .label {
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Watermark" class="watermark" onerror="this.src='https://via.placeholder.com/400x400?text=LOGO'">

        <div class="no-print mb-3">
            <button class="btn btn-print btn-sm" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak
            </button>
            <button class="btn btn-close btn-sm" onclick="window.close()">
                <i class="bi bi-x"></i> Tutup
            </button>
        </div>

        <div class="header">
            <div class="logo-container">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Awan Barbershop" class="logo" onerror="this.src='https://via.placeholder.com/80x80?text=LOGO'">
                <div class="header-text">
                    <h2><?= $nama_perusahaan ?? 'AWAN BARBERSHOP' ?></h2>
                    <p><?= $alamat_perusahaan ?? 'Jl. Contoh No. 123, Kota' ?> | Telp: <?= $telepon ?? '081234567890' ?></p>
                    <p>Email: <?= $email ?? 'info@awanbarbershop.com' ?> | Website: <?= $website ?? 'www.awanbarbershop.com' ?></p>
                </div>
            </div>
        </div>

        <div class="report-title"><?= $report_title ?? 'LAPORAN' ?></div>
        <div class="report-date">
            Tanggal: <?= $tanggal ?? date('d F Y') ?>
        </div>

        <!-- Konten laporan -->
        <?= $content ?? '' ?>

        <div class="footer">
            <div class="signature">
                <p>
                    Mengetahui,
                </p>
                <p class="name"><?= $manager ?? 'Pimpinan' ?></p>
            </div>
        </div>
    </div>

    <script>
        // Tidak ada auto-print saat halaman dimuat
    </script>
</body>

</html>