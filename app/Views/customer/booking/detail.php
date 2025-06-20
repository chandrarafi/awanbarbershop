<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="py-16">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold gradient-text">Detail Booking</h1>
                <p class="text-white mt-2">Kode Booking: <?= $booking['kdbooking'] ?></p>
            </div>
            <div class="flex gap-2">
                <a href="<?= site_url('customer/booking') ?>" class="btn-secondary px-6 py-3 rounded-full inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali</span>
                </a>
                <button id="btnPrint" class="btn-primary px-6 py-3 rounded-full inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Faktur</span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <div id="printArea">
                    <!-- Faktur untuk dicetak -->
                    <div id="fakturPrint">
                        <!-- Header -->
                        <div class="flex flex-wrap justify-between items-center pb-6 border-b">
                            <div class="flex items-center mb-4 md:mb-0">
                                <div class="mr-4">
                                    <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" class="h-16">
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-black">Awan Barbershop</h2>
                                    <p class="text-sm text-gray-700">Jl. Contoh No. 123, Kota</p>
                                    <p class="text-sm text-gray-700">Telp: (021) 123-4567</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <h3 class="text-xl font-bold text-gray-800">INVOICE #<?= $booking['kdbooking'] ?></h3>
                                <p class="text-sm text-gray-700">
                                    Tanggal Invoice: <?= date('d F Y') ?><br>
                                    Tanggal Booking: <?= date('d F Y', strtotime($booking['tanggal_booking'])) ?>
                                </p>
                                <span class="inline-block mt-2 py-1 px-3 
                                    <?php
                                    switch ($booking['status']) {
                                        case 'pending':
                                            echo 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'confirmed':
                                            echo 'bg-blue-100 text-blue-800';
                                            break;
                                        case 'completed':
                                            echo 'bg-green-100 text-green-800';
                                            break;
                                        case 'cancelled':
                                            echo 'bg-red-100 text-red-800';
                                            break;
                                        case 'no-show':
                                            echo 'bg-gray-100 text-gray-800';
                                            break;
                                        default:
                                            echo 'bg-gray-100 text-gray-800';
                                    }
                                    ?>
                                    text-xs font-medium rounded-full">
                                    <?php
                                    switch ($booking['status']) {
                                        case 'pending':
                                            echo 'Menunggu Konfirmasi';
                                            break;
                                        case 'confirmed':
                                            echo 'Terkonfirmasi';
                                            break;
                                        case 'completed':
                                            echo 'Selesai';
                                            break;
                                        case 'cancelled':
                                            echo 'Dibatalkan';
                                            break;
                                        case 'no-show':
                                            echo 'Tidak Hadir';
                                            break;
                                        default:
                                            echo $booking['status'];
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>

                        <!-- Watermark -->
                        <?php if ($booking['total'] <= $booking['jumlahbayar']): ?>
                            <div class="faktur-watermark lunas text-green-500 font-bold">LUNAS</div>
                        <?php else: ?>
                            <div class="faktur-watermark belum-lunas text-red-500 font-bold">BELUM LUNAS</div>
                        <?php endif; ?>

                        <!-- Content -->
                        <div class="py-6">
                            <!-- Customer & Booking Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div>
                                    <h4 class="text-sm uppercase text-gray-600 font-medium mb-2">Informasi Pelanggan</h4>
                                    <p class="font-medium text-gray-800"><?= $booking['nama_lengkap'] ?></p>
                                    <p class="text-gray-700"><?= $booking['no_hp'] ?></p>
                                    <p class="text-gray-700"><?= $booking['email'] ?? '-' ?></p>
                                    <p class="text-gray-700"><?= $booking['alamat'] ?? '-' ?></p>
                                </div>
                                <div>
                                    <h4 class="text-sm uppercase text-gray-600 font-medium mb-2">Informasi Booking</h4>
                                    <p class="text-gray-700"><span class="font-medium text-gray-800">Tanggal:</span> <?= date('d F Y', strtotime($booking['tanggal_booking'])) ?></p>
                                    <?php if (!empty($details) && isset($details[0])): ?>
                                        <p class="text-gray-700"><span class="font-medium text-gray-800">Waktu:</span> <?= $details[0]['jamstart'] ?> - <?= $details[0]['jamend'] ?></p>
                                        <p class="text-gray-700"><span class="font-medium text-gray-800">Karyawan:</span> <?= $details[0]['idkaryawan'] ? ($details[0]['nama_karyawan'] ?? 'Unknown') : 'Belum ditentukan' ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Services -->
                            <div class="mb-8">
                                <h4 class="text-sm uppercase text-gray-600 font-medium mb-2">Detail Layanan</h4>
                                <div class="bg-gray-50 rounded-lg overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-700 uppercase">Layanan</th>
                                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-700 uppercase">Deskripsi</th>
                                                <th class="py-3 px-4 text-right text-xs font-medium text-gray-700 uppercase">Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <?php if (!empty($details)): ?>
                                                <?php foreach ($details as $detail): ?>
                                                    <tr>
                                                        <td class="py-3 px-4 text-gray-800"><?= $detail['nama_paket'] ?></td>
                                                        <td class="py-3 px-4 text-gray-700"><?= $detail['deskripsi'] ?></td>
                                                        <td class="py-3 px-4 text-right text-gray-800">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="3" class="py-4 px-4 text-center text-gray-600">Tidak ada data layanan</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-gray-50">
                                                <th colspan="2" class="py-3 px-4 text-right font-medium text-gray-800">Total</th>
                                                <th class="py-3 px-4 text-right font-medium text-gray-800">Rp <?= number_format($booking['total'], 0, ',', '.') ?></th>
                                            </tr>
                                            <tr class="bg-gray-50">
                                                <th colspan="2" class="py-3 px-4 text-right font-medium text-gray-800">Dibayar</th>
                                                <th class="py-3 px-4 text-right font-medium text-green-700">Rp <?= number_format($booking['jumlahbayar'], 0, ',', '.') ?></th>
                                            </tr>
                                            <tr class="bg-gray-50">
                                                <th colspan="2" class="py-3 px-4 text-right font-medium text-gray-800">Sisa</th>
                                                <th class="py-3 px-4 text-right font-medium <?= ($booking['total'] - $booking['jumlahbayar'] > 0) ? 'text-red-700' : 'text-green-700' ?>">Rp <?= number_format($booking['total'] - $booking['jumlahbayar'], 0, ',', '.') ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <?php if (!empty($pembayaran)): ?>
                                <!-- Payment History -->
                                <div class="mb-8">
                                    <h4 class="text-sm uppercase text-gray-600 font-medium mb-2">Riwayat Pembayaran</h4>
                                    <div class="bg-gray-50 rounded-lg overflow-hidden">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-700 uppercase">Tanggal</th>
                                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-700 uppercase">Metode</th>
                                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-700 uppercase">Jenis</th>
                                                    <th class="py-3 px-4 text-right text-xs font-medium text-gray-700 uppercase">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <?php foreach ($pembayaran as $bayar): ?>
                                                    <tr>
                                                        <td class="py-3 px-4 text-gray-800"><?= date('d/m/Y H:i', strtotime($bayar['created_at'])) ?></td>
                                                        <td class="py-3 px-4 text-gray-800"><?= ucfirst($bayar['metode']) ?></td>
                                                        <td class="py-3 px-4">
                                                            <span class="inline-block py-1 px-2 rounded-full text-xs <?= $bayar['status'] == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                                                <?= $bayar['status'] == 'paid' ? 'Lunas' : 'Pending' ?>
                                                            </span>
                                                        </td>
                                                        <td class="py-3 px-4">
                                                            <span class="inline-block py-1 px-2 rounded-full text-xs <?= ($bayar['jenis'] ?? '') == 'DP' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' ?>">
                                                                <?= ($bayar['jenis'] ?? 'Lunas') ?>
                                                            </span>
                                                        </td>
                                                        <td class="py-3 px-4 text-right text-gray-800">Rp <?= number_format($bayar['total_bayar'], 0, ',', '.') ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- QR Code -->
                            <div class="mb-8 flex justify-end">
                                <div class="text-center">
                                    <div id="qrcode" class="inline-block p-2 bg-white border border-gray-200 rounded-md"></div>
                                    <p class="text-xs text-gray-500 mt-1">Scan untuk verifikasi</p>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-8">
                                <h4 class="text-sm uppercase text-gray-600 font-medium mb-2">Catatan</h4>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-700">
                                        1. Harap datang 10 menit sebelum jadwal yang ditentukan.<br>
                                        2. Pembatalan harus dilakukan minimal 2 jam sebelum jadwal.<br>
                                        3. Sisa pembayaran dilakukan setelah layanan selesai.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="pt-6 border-t">
                            <div class="text-center">
                                <p class="text-sm text-gray-700">Terima kasih telah menggunakan layanan kami</p>
                                <p class="text-xs text-gray-600 mt-1">© <?= date('Y') ?> Awan Barbershop - Faktur ini sah tanpa tanda tangan</p>
                                <p class="text-xs text-gray-500 mt-1">Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function() {
        $('#btnPrint').on('click', function() {
            const printContents = document.getElementById('printArea').innerHTML;
            const originalContents = document.body.innerHTML;

            const printStyles = `
                <style>
                    @media print {
                        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; font-size: 14px; }
                        h1, h2, h3, h4 { margin-top: 0; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { padding: 8px; text-align: left; }
                        th { background-color: #f3f4f6; }
                        .text-right { text-align: right; }
                        .text-center { text-align: center; }
                        .border-t { border-top: 1px solid #e5e7eb; }
                        .border-b { border-bottom: 1px solid #e5e7eb; }
                    }
                </style>
            `;

            const printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Booking Detail</title>' + printStyles + '</head><body>');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');

            printWindow.document.close();
            printWindow.focus();

            // Delay before print
            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 500);
        });
    });
</script>
<?= $this->endSection() ?>