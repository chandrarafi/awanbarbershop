<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Karyawan</title>
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
                    <h2><?= $nama_perusahaan ?></h2>
                    <p><?= $alamat_perusahaan ?> | Telp: <?= $telepon ?></p>
                    <p>Email: <?= $email ?> | Website: <?= $website ?></p>
                </div>
            </div>
        </div>

        <div class="report-title">LAPORAN DATA KARYAWAN</div>
        <div class="report-date">
            Tanggal: <?= $tanggal ?>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Kode Karyawan</th>
                    <th>Nama Karyawan</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($karyawan as $k) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $k['idkaryawan'] ?></td>
                        <td><?= $k['namakaryawan'] ?></td>
                        <td class="text-center">
                            <?php
                            if ($k['jenkel'] == 'L') {
                                echo 'Laki-laki';
                            } elseif ($k['jenkel'] == 'P') {
                                echo 'Perempuan';
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td><?= $k['alamat'] ?? '-' ?></td>
                        <td><?= $k['nohp'] ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end fw-bold">Total Karyawan:</td>
                    <td class="text-center fw-bold"><?= count($karyawan) ?> orang</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <div class="signature">
                <p>
                    Mengetahui,
                </p>
                <p class="name"><?= $manager ?></p>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Auto print when page loads
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>

</html>