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
                <?php
                // Cek apakah booking expired berdasarkan field expired_at
                $isExpired = isset($booking['expired_at']) && strtotime($booking['expired_at']) < time() && $booking['jenispembayaran'] == 'Belum Bayar';
                $needsPayment = $booking['total'] > $booking['jumlahbayar'] && $booking['jenispembayaran'] !== 'Lunas';

                if ($needsPayment && !$isExpired && $booking['status'] !== 'expired'):
                ?>
                    <a href="<?= site_url('customer/booking/payment/' . $booking['kdbooking']) ?>" class="btn-primary px-6 py-3 rounded-full inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                        <span>Lanjutkan Pembayaran</span>
                    </a>
                <?php endif; ?>
                <button id="btnPrint" class="btn-primary px-6 py-3 rounded-full inline-flex items-center" onclick="printFaktur()">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Faktur</span>
                </button>
            </div>
        </div>

        <?php if ($isExpired || $booking['status'] === 'expired'): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            <span class="font-medium">Booking Dibatalkan!</span> Waktu pembayaran telah habis. Silakan <a href="<?= site_url('customer/booking/create') ?>" class="font-medium underline text-red-700 hover:text-red-600">buat booking baru</a>.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

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
                                    <p class="text-sm text-gray-700">Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangah, Kec. Pauh, Kota Padang, Sumatera Barat 25127</p>
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

                                    <!-- Status Pembayaran -->
                                    <p class="text-gray-700 mt-2">
                                        <span class="font-medium text-gray-800">Status Pembayaran:</span>
                                        <?php if ($booking['jenispembayaran'] == 'Belum Bayar'): ?>
                                            <span class="inline-block py-1 px-2 rounded-full text-xs bg-red-100 text-red-800">Belum Bayar</span>
                                        <?php elseif ($booking['jenispembayaran'] == 'DP' && $booking['total'] > $booking['jumlahbayar']): ?>
                                            <span class="inline-block py-1 px-2 rounded-full text-xs bg-yellow-100 text-yellow-800">DP (<?= round(($booking['jumlahbayar'] / $booking['total']) * 100) ?>%)</span>
                                        <?php elseif ($booking['total'] <= $booking['jumlahbayar']): ?>
                                            <span class="inline-block py-1 px-2 rounded-full text-xs bg-green-100 text-green-800">Lunas</span>
                                        <?php else: ?>
                                            <span class="inline-block py-1 px-2 rounded-full text-xs bg-red-100 text-red-800">Belum Bayar</span>
                                        <?php endif; ?>
                                    </p>

                                    <!-- Status Booking -->
                                    <p class="text-gray-700 mt-1">
                                        <span class="font-medium text-gray-800">Status Booking:</span>
                                        <span class="inline-block py-1 px-2 rounded-full text-xs 
                                        <?php
                                        // Cek apakah booking expired berdasarkan field expired_at
                                        $isExpired = isset($booking['expired_at']) && strtotime($booking['expired_at']) < time() && $booking['jenispembayaran'] == 'Belum Bayar';

                                        if ($isExpired) {
                                            echo 'bg-red-100 text-red-800';
                                        } else {
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
                                                case 'expired':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                case 'no-show':
                                                    echo 'bg-gray-100 text-gray-800';
                                                    break;
                                                default:
                                                    echo 'bg-gray-100 text-gray-800';
                                            }
                                        }
                                        ?>">
                                            <?php
                                            if ($isExpired) {
                                                echo 'Batal (Waktu Habis)';
                                            } else {
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
                                                    case 'expired':
                                                        echo 'Batal (Waktu Habis)';
                                                        break;
                                                    case 'no-show':
                                                        echo 'Tidak Hadir';
                                                        break;
                                                    default:
                                                        echo $booking['status'];
                                                }
                                            }
                                            ?>
                                        </span>
                                    </p>
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
                                                <th class="py-3 px-4 text-right font-medium text-gray-800">Rp <?= number_format($booking['total'] ?? 0, 0, ',', '.') ?></th>
                                            </tr>
                                            <tr class="bg-gray-50">
                                                <th colspan="2" class="py-3 px-4 text-right font-medium text-gray-800">Dibayar</th>
                                                <th class="py-3 px-4 text-right font-medium text-green-700">Rp <?= number_format($booking['jumlahbayar'] ?? 0, 0, ',', '.') ?></th>
                                            </tr>
                                            <tr class="bg-gray-50">
                                                <th colspan="2" class="py-3 px-4 text-right font-medium text-gray-800">Sisa</th>
                                                <th class="py-3 px-4 text-right font-medium <?= (($booking['total'] ?? 0) - ($booking['jumlahbayar'] ?? 0) > 0) ? 'text-red-700' : 'text-green-700' ?>">Rp <?= number_format(($booking['total'] ?? 0) - ($booking['jumlahbayar'] ?? 0), 0, ',', '.') ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- Ringkasan Status Pembayaran -->
                            <div class="mb-8">
                                <h4 class="text-sm uppercase text-gray-600 font-medium mb-2">Status Pembayaran</h4>
                                <div class="bg-gray-50 p-5 rounded-lg">
                                    <div class="flex flex-col md:flex-row gap-4">
                                        <!-- Status -->
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <div class="rounded-full p-1 mr-2 
                                                <?php if ($booking['total'] <= $booking['jumlahbayar']): ?>
                                                    bg-green-100 text-green-700
                                                <?php elseif ($booking['jenispembayaran'] == 'DP'): ?>
                                                    bg-yellow-100 text-yellow-700
                                                <?php else: ?>
                                                    bg-red-100 text-red-700
                                                <?php endif; ?>
                                                ">
                                                    <?php if ($booking['total'] <= $booking['jumlahbayar']): ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    <?php elseif ($booking['jenispembayaran'] == 'DP'): ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    <?php else: ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    <?php endif; ?>
                                                </div>
                                                <h3 class="text-lg font-medium text-gray-800">
                                                    <?php if ($booking['total'] <= $booking['jumlahbayar']): ?>
                                                        Pembayaran Lunas
                                                    <?php elseif ($booking['jenispembayaran'] == 'DP'): ?>
                                                        Pembayaran DP (<?= round(($booking['jumlahbayar'] / $booking['total']) * 100) ?>%)
                                                    <?php else: ?>
                                                        Belum Bayar
                                                    <?php endif; ?>
                                                </h3>
                                            </div>
                                            <p class="text-gray-600 ml-8">
                                                <?php if ($booking['total'] <= $booking['jumlahbayar']): ?>
                                                    Anda telah melunasi seluruh pembayaran untuk booking ini.
                                                <?php elseif ($booking['jenispembayaran'] == 'DP'): ?>
                                                    Anda telah membayar DP sebesar Rp <?= number_format($booking['jumlahbayar'], 0, ',', '.') ?>.
                                                    Sisa pembayaran: Rp <?= number_format($booking['total'] - $booking['jumlahbayar'], 0, ',', '.') ?>.
                                                <?php else: ?>
                                                    Anda belum melakukan pembayaran untuk booking ini.
                                                <?php endif; ?>
                                            </p>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="flex-1">
                                            <div class="mb-2">
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-sm font-medium text-gray-700">Progress Pembayaran</span>
                                                    <span class="text-sm font-medium text-gray-700">
                                                        <?php
                                                        $percentage = $booking['total'] > 0 ?
                                                            round(($booking['jumlahbayar'] / $booking['total']) * 100) : 0;
                                                        echo $percentage . '%';
                                                        ?>
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                    <div class="
                                                    <?php if ($booking['total'] <= $booking['jumlahbayar']): ?>
                                                        bg-green-500
                                                    <?php elseif ($booking['jenispembayaran'] == 'DP'): ?>
                                                        bg-yellow-500
                                                    <?php else: ?>
                                                        bg-red-500
                                                    <?php endif; ?>
                                                    h-2.5 rounded-full" style="width: <?= $percentage ?>%"></div>
                                                </div>
                                            </div>
                                            <div class="flex justify-between text-sm text-gray-600">
                                                <span>Rp 0</span>
                                                <span>Rp <?= number_format($booking['total'], 0, ',', '.') ?></span>
                                            </div>
                                        </div>
                                    </div>
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
                                                                <?= $bayar['status'] == 'paid' ? 'Dibayar' : 'Belum Dibayar' ?>
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

                            <!-- Notes -->
                            <div class="mb-8">
                                <h4 class="text-sm uppercase text-gray-600 font-medium mb-2">Catatan</h4>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-700">
                                        1. Harap datang 10 menit sebelum jadwal yang ditentukan.<br>
                                        2. Jika tidak datang maka dp akan hangus<br>
                                        3. Pelanggan tidak dapat membatalkan booking<br>
                                        4. Sisa pembayaran dilakukan setelah layanan selesai.
                                    </p>
                                </div>
                            </div>

                            <?php
                            // Cek apakah booking expired berdasarkan field expired_at jika belum didefinisikan
                            if (!isset($isExpired)) {
                                $isExpired = isset($booking['expired_at']) && strtotime($booking['expired_at']) < time() && $booking['jenispembayaran'] == 'Belum Bayar';
                            }

                            // Cek apakah sudah ada pembayaran sebelumnya (tidak peduli jumlahnya)
                            $hasPreviousPayment = !empty($pembayaran);

                            // Tampilkan tombol bayar hanya jika belum ada pembayaran, tidak expired, dan bukan lunas
                            if ($booking['jenispembayaran'] == 'Belum Bayar' && $booking['status'] !== 'expired' && !$isExpired):
                            ?>
                                <!-- Payment Action -->
                                <div class="mb-8 no-print">
                                    <div class="bg-blue-50 p-5 rounded-lg border border-blue-200 text-center">
                                        <h4 class="text-lg font-medium text-blue-800 mb-2">Lanjutkan Pembayaran</h4>
                                        <p class="text-blue-700 mb-4">Silahkan melakukan pembayaran sebesar <span class="font-bold">Rp <?= number_format($booking['total'], 0, ',', '.') ?></span></p>
                                        <a href="<?= site_url('customer/booking/payment/' . $booking['kdbooking']) ?>" class="inline-block bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md">
                                            <div class="flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                </svg>
                                                Bayar Sekarang
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php elseif (($isExpired || $booking['status'] === 'expired') && $booking['jenispembayaran'] == 'Belum Bayar'): ?>
                                <!-- Expired Payment Warning -->
                                <div class="mb-8 no-print">
                                    <div class="bg-red-50 p-5 rounded-lg border border-red-200 text-center">
                                        <div class="flex items-center justify-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h4 class="text-lg font-medium text-red-800">Waktu Pembayaran Habis</h4>
                                        </div>
                                        <p class="text-red-700 mb-4">Booking ini telah dibatalkan karena melewati batas waktu pembayaran.</p>
                                        <a href="<?= site_url('customer/booking/create') ?>" class="inline-block bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-md">
                                            <div class="flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Buat Booking Baru
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
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

<?= $this->section('custom_script') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk mencetak faktur
        function printFaktur() {
            // Simpan konten asli
            const printContents = document.getElementById('fakturPrint').innerHTML;
            const originalContents = document.body.innerHTML;

            // Persiapkan halaman cetak
            const printStyles = `
                <style>
                    @media print {
                        body { 
                            font-family: Arial, sans-serif; 
                            padding: 0;
                            margin: 0;
                            color: #333;
                            height: 100%;
                            overflow: hidden;
                        }
                        html {
                            height: 100%;
                            overflow: hidden;
                        }
                        .faktur-watermark {
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%) rotate(-45deg);
                            font-size: 60px;
                            opacity: 0.15;
                            z-index: 1000;
                        }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { padding: 2px !important; text-align: left; }
                        thead { background-color: #f3f4f6 !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        .no-print { display: none !important; }
                        .rounded-lg { border-radius: 4px; }
                        .rounded-full { border-radius: 9999px; }
                        .bg-gray-50 { background-color: #f9fafb !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        .bg-gray-100 { background-color: #f3f4f6 !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        .bg-blue-50 { background-color: #eff6ff !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        .bg-red-50 { background-color: #fef2f2 !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        .bg-green-50 { background-color: #f0fdf4 !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        .bg-yellow-50 { background-color: #fffbeb !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        .bg-white { background-color: #ffffff !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        .text-gray-600 { color: #4b5563 !important; }
                        .text-gray-700 { color: #374151 !important; }
                        .text-gray-800 { color: #1f2937 !important; }
                        .font-medium { font-weight: 500 !important; }
                        .font-bold { font-weight: 700 !important; }
                        .border { border: 1px solid #e5e7eb !important; }
                        .border-t { border-top: 1px solid #e5e7eb !important; }
                        .border-b { border-bottom: 1px solid #e5e7eb !important; }
                        .mt-1 { margin-top: 0.15rem !important; }
                        .mt-2 { margin-top: 0.25rem !important; }
                        .mb-2 { margin-bottom: 0.25rem !important; }
                        .mb-4 { margin-bottom: 0.5rem !important; }
                        .mb-8 { margin-bottom: 0.75rem !important; }
                        .p-4 { padding: 0.5rem !important; }
                        .p-5 { padding: 0.5rem !important; }
                        .p-6 { padding: 0.5rem !important; }
                        .py-3 { padding-top: 0.25rem !important; padding-bottom: 0.25rem !important; }
                        .px-4 { padding-left: 0.5rem !important; padding-right: 0.5rem !important; }
                        .text-center { text-align: center !important; }
                        .text-right { text-align: right !important; }
                        .text-sm { font-size: 0.75rem !important; }
                        .text-xs { font-size: 0.65rem !important; }
                        .text-lg { font-size: 0.9rem !important; }
                        .text-xl { font-size: 1rem !important; }
                        .text-3xl { font-size: 1.25rem !important; }
                        .uppercase { text-transform: uppercase !important; }
                        .inline-block { display: inline-block !important; }
                        .py-1 { padding-top: 0.15rem !important; padding-bottom: 0.15rem !important; }
                        .px-2 { padding-left: 0.25rem !important; padding-right: 0.25rem !important; }
                        .text-green-800 { color: #065f46 !important; }
                        .bg-green-100 { background-color: #d1fae5 !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        .text-red-800 { color: #991b1b !important; }
                        .bg-red-100 { background-color: #fee2e2 !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        .text-yellow-800 { color: #92400e !important; }
                        .bg-yellow-100 { background-color: #fef3c7 !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        .text-blue-800 { color: #1e40af !important; }
                        .bg-blue-100 { background-color: #dbeafe !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        .grid { display: grid !important; }
                        .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
                        .gap-6 { gap: 0.75rem !important; }
                        
                        /* Tambahan untuk memastikan tampilan cetak baik */
                        @page {
                            size: A4 portrait;
                            margin: 5mm;
                        }
                        
                        /* Pastikan konten muat dalam satu halaman */
                        #faktur-container {
                            width: 100%;
                            max-width: 100%;
                            box-sizing: border-box;
                            page-break-after: avoid;
                            page-break-before: avoid;
                            page-break-inside: avoid;
                            font-size: 10px;
                            transform: scale(0.85);
                            transform-origin: top center;
                            max-height: 100%;
                            overflow: hidden;
                            background-color: #fff;
                            color-adjust: exact;
                            print-color-adjust: exact;
                            -webkit-print-color-adjust: exact;
                        }
                        
                        /* Sembunyikan elemen yang tidak perlu dicetak */
                        button, .btn-primary, .btn-secondary, a.btn-primary, a.btn-secondary, .no-print {
                            display: none !important;
                        }
                        
                        /* Ukuran teks yang lebih kecil untuk muat di satu halaman */
                        h1, h2, h3, h4 { 
                            font-size: 90% !important;
                            margin: 2px 0 !important;
                        }
                        p { 
                            font-size: 80% !important; 
                            margin: 2px 0 !important;
                            line-height: 1.2 !important;
                        }
                        th, td { 
                            padding: 2px !important; 
                            font-size: 80% !important;
                            line-height: 1.2 !important;
                        }
                        
                        /* Kurangi spasi pada tabel */
                        table {
                            margin-bottom: 0.5rem !important;
                        }
                        tbody tr td, thead tr th {
                            padding: 2px 4px !important;
                        }
                        
                        /* Kurangi ukuran logo */
                        img.h-16 {
                            height: 40px !important;
                        }
                        
                        /* Kurangi spasi pada header dan footer */
                        .pb-6 {
                            padding-bottom: 0.5rem !important;
                        }
                        .pt-6 {
                            padding-top: 0.5rem !important;
                        }
                        
                        /* Mencegah halaman kosong */
                        * {
                            print-color-adjust: exact !important;
                            -webkit-print-color-adjust: exact !important;
                            color-adjust: exact !important;
                        }
                        
                        /* Mencegah halaman tambahan */
                        #faktur-print-wrapper {
                            height: 100%;
                            overflow: hidden;
                            position: relative;
                            page-break-after: avoid;
                            background-color: #fff;
                        }
                        
                        /* Hapus footer halaman */
                        @page {
                            margin-bottom: 0;
                        }
                        
                        /* Pastikan background warna muncul */
                        .bg-white {
                            background-color: #fff !important;
                            print-color-adjust: exact;
                            -webkit-print-color-adjust: exact;
                        }
                    }
                </style>
            `;

            // Ganti konten body dengan konten yang akan dicetak
            document.body.innerHTML = printStyles + '<div id="faktur-print-wrapper"><div id="faktur-container" style="padding: 8px;">' + printContents + '</div></div>';

            // Cetak
            setTimeout(() => {
                window.print();

                // Kembalikan konten asli
                setTimeout(() => {
                    document.body.innerHTML = originalContents;

                    // Reinisialisasi event listener setelah konten dikembalikan
                    setTimeout(function() {
                        document.getElementById('btnPrint').addEventListener('click', printFaktur);
                    }, 100);
                }, 100);
            }, 100);
        }

        // Tambahkan event listener ke tombol cetak
        document.getElementById('btnPrint').addEventListener('click', printFaktur);

        function checkExpiredBooking() {
            const kdbooking = '<?= $booking['kdbooking'] ?>';
            const jenispembayaran = '<?= $booking['jenispembayaran'] ?>';
            const status = '<?= $booking['status'] ?>';

            // Hanya periksa jika belum bayar dan status masih pending
            if (jenispembayaran === 'Belum Bayar' && status === 'pending') {
                // Periksa apakah booking sudah expired
                <?php if (isset($booking['expired_at'])): ?>
                    const expiredAt = new Date('<?= $booking['expired_at'] ?>').getTime();
                    const now = new Date().getTime();

                    if (now > expiredAt) {
                        // Kirim permintaan AJAX untuk memperbarui status booking menjadi expired
                        fetch('<?= site_url('customer/booking/expire/') ?>' + kdbooking, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    console.log('Booking berhasil diperbarui menjadi expired');
                                    // Refresh halaman untuk menampilkan status terbaru
                                    window.location.reload();
                                } else {
                                    console.error('Gagal memperbarui status booking:', data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                <?php endif; ?>
            }
        }

        // Jalankan pemeriksaan saat halaman dimuat
        checkExpiredBooking();

        // Jalankan pemeriksaan setiap 30 detik
        setInterval(checkExpiredBooking, 30000);

        // Tambahkan pemeriksaan global menggunakan cron endpoint
        function checkAllExpiredBookings() {
            fetch('<?= site_url('cron/check-expired-bookings') ?>?key=awanbarbershop_secret_key', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' && data.processed > 0) {
                        console.log('Berhasil memperbarui ' + data.processed + ' booking yang expired');
                        // Reload halaman jika ada booking yang diperbarui
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error checking expired bookings:', error);
                });
        }

        // Jalankan pemeriksaan global setiap 60 detik
        setInterval(checkAllExpiredBookings, 60000);
    });
</script>
<?= $this->endSection() ?>