<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="py-16">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold gradient-text">Riwayat Booking</h1>
                <p class="text-gray-300 mt-2">Daftar booking Anda</p>
            </div>
            <a href="<?= site_url('customer/booking/create') ?>" class="btn-primary px-6 py-3 rounded-full inline-flex items-center transform hover:scale-105 transition-all duration-300">
                <span>Booking Baru</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            <?php if (empty($bookings)) : ?>
                <div id="empty-state" class="text-center py-10">
                    <img src="<?= base_url('assets/images/imgnotfound.jpg') ?>" alt="Belum ada booking" class="w-60 h-60 mx-auto mb-4 opacity-75 object-cover rounded-lg">
                    <h3 class="text-xl font-semibold text-gray-800">Belum Ada Riwayat Booking</h3>
                    <p class="text-gray-600 mb-6">Anda belum memiliki riwayat booking. Silakan buat booking baru untuk memulai.</p>
                    <a href="<?= site_url('customer/booking/create') ?>" class="btn-primary px-6 py-3 rounded-full inline-flex items-center">
                        <span>Buat Booking Sekarang</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </a>
                </div>
            <?php else : ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 text-gray-700">
                                <th class="py-3 px-4 font-medium">No. Booking</th>
                                <th class="py-3 px-4 font-medium">Tanggal</th>
                                <th class="py-3 px-4 font-medium">Layanan</th>
                                <th class="py-3 px-4 font-medium">Status</th>
                                <th class="py-3 px-4 font-medium">Total</th>
                                <th class="py-3 px-4 font-medium text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking) : ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <span class="font-medium text-gray-800 "><?= $booking['kdbooking'] ?></span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-gray-800"><?= date('d F Y', strtotime($booking['tanggal_booking'])) ?></span><br>
                                        <span class="text-xs text-gray-600">
                                            <?= $booking['jamstart'] ?? '-' ?> - <?= $booking['jamend'] ?? '-' ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div>
                                            <span class="font-medium text-gray-800"><?= $booking['nama_paket'] ?? 'Paket tidak ditemukan' ?></span><br>
                                            <span class="text-xs text-gray-600">Karyawan: <?= $booking['namakaryawan'] ?? 'Belum ditentukan' ?></span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <?php
                                        $badgeClass = '';
                                        switch ($booking['status']) {
                                            case 'pending':
                                                $badgeClass = 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'confirmed':
                                                $badgeClass = 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'completed':
                                                $badgeClass = 'bg-green-100 text-green-800';
                                                break;
                                            case 'cancelled':
                                                $badgeClass = 'bg-red-100 text-red-800';
                                                break;
                                            case 'no-show':
                                                $badgeClass = 'bg-gray-100 text-gray-800';
                                                break;
                                            case 'rejected':
                                                $badgeClass = 'bg-rose-100 text-rose-800';
                                                break;
                                            default:
                                                $badgeClass = 'bg-gray-100 text-gray-800';
                                        }
                                        ?>
                                        <span class="inline-block py-1 px-2 <?= $badgeClass ?> text-xs font-medium rounded-full w-40 text-center">
                                            <?= $booking['status_text'] ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-medium text-gray-800"><?= $booking['total_formatted'] ?></span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="<?= site_url('customer/booking/detail/' . $booking['kdbooking']) ?>"
                                            class="inline-flex items-center justify-center p-2 bg-blue-50 text-blue-700 rounded-full hover:bg-blue-100 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="mt-8 text-sm text-gray-700">
                <h3 class="text-lg font-semibold mb-2">Informasi Status Booking:</h3>
                <ul class="space-y-2">
                    <li class="flex items-center">
                        <span class="inline-block w-30 py-1 px-2 bg-yellow-100 text-yellow-800 text-xs font-medium text-center rounded-full">Menunggu Konfirmasi</span>
                        <span class="ml-2 text-gray-700">Booking Anda sedang menunggu konfirmasi dari kami</span>
                    </li>
                    <li class="flex items-center">
                        <span class="inline-block w-24 py-1 px-2 bg-blue-100 text-blue-800 text-xs font-medium text-center rounded-full">Terkonfirmasi</span>
                        <span class="ml-2 text-gray-700">Booking Anda telah dikonfirmasi</span>
                    </li>
                    <li class="flex items-center">
                        <span class="inline-block w-24 py-1 px-2 bg-green-100 text-green-800 text-xs font-medium text-center rounded-full">Selesai</span>
                        <span class="ml-2 text-gray-700">Layanan telah selesai diberikan</span>
                    </li>
                    <li class="flex items-center">
                        <span class="inline-block w-24 py-1 px-2 bg-red-100 text-red-800 text-xs font-medium text-center rounded-full">Dibatalkan</span>
                        <span class="ml-2 text-gray-700">Booking telah dibatalkan</span>
                    </li>
                    <li class="flex items-center">
                        <span class="inline-block w-24 py-1 px-2 bg-gray-100 text-gray-800 text-xs font-medium text-center rounded-full">Tidak Hadir</span>
                        <span class="ml-2 text-gray-700">Anda tidak hadir pada waktu yang telah ditentukan</span>
                    </li>
                    <li class="flex items-center">
                        <span class="inline-block w-24 py-1 px-2 bg-rose-100 text-rose-800 text-xs font-medium text-center rounded-full">Ditolak</span>
                        <span class="ml-2 text-gray-700">Booking Anda telah ditolak oleh admin</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>