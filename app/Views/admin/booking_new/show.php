<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .booking-detail-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .info-card {
        margin-bottom: 20px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .info-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        padding: 15px 20px;
    }

    .info-card .card-header h5 {
        margin: 0;
        color: #495057;
        font-weight: 600;
    }

    .info-card .card-body {
        padding: 20px;
    }

    .booking-id {
        font-size: 24px;
        font-weight: 700;
        color: #007bff;
        margin-bottom: 5px;
    }

    .booking-date {
        font-size: 16px;
        color: #6c757d;
        margin-bottom: 15px;
    }

    .booking-status {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 14px;
        text-transform: uppercase;
    }

    .status-pending {
        background-color: #ffc107;
        color: #212529;
    }

    .status-confirmed {
        background-color: #0dcaf0;
        color: #fff;
    }

    .status-completed {
        background-color: #198754;
        color: #fff;
    }

    .status-cancelled {
        background-color: #dc3545;
        color: #fff;
    }

    .status-no-show {
        background-color: #6c757d;
        color: #fff;
    }

    .status-rejected {
        background-color: #dc3545;
        color: #fff;
    }

    .detail-table th {
        width: 35%;
        font-weight: 600;
    }

    .service-table th,
    .service-table td {
        vertical-align: middle;
    }

    .payment-info {
        border-top: 1px solid #dee2e6;
        margin-top: 20px;
        padding-top: 20px;
    }

    .payment-info h6 {
        font-weight: 600;
        margin-bottom: 15px;
    }

    .total-section {
        font-size: 18px;
        font-weight: 700;
    }

    .print-section {
        margin: 30px 0;
    }

    @media print {
        .no-print {
            display: none !important;
        }

        .print-only {
            display: inline-block !important;
        }

        body {
            padding: 0;
            margin: 0;
        }

        .booking-detail-container {
            width: 100%;
            max-width: 100%;
        }

        .info-card {
            box-shadow: none;
            border: 1px solid #ddd;
        }
    }

    .invoice-header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .invoice-header img {
        max-width: 100px;
        height: auto;
    }

    .invoice-title {
        font-size: 22px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 5px;
        text-align: right;
    }

    .invoice-number {
        font-size: 16px;
        color: #6c757d;
        text-align: right;
    }

    /* Style untuk Modal Bukti Pembayaran */
    #buktiModal .modal-content {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    #buktiModal .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    #buktiModal .modal-body {
        padding: 20px;
    }

    #buktiImage {
        border-radius: 5px;
        max-width: 100%;
        transition: transform 0.3s ease, max-height 0.3s ease;
        cursor: zoom-in;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    #buktiImage:hover {
        transform: scale(1.02);
    }

    #buktiImage.zoomed {
        cursor: zoom-out;
        transform: scale(1);
    }

    #buktiImage.zoomed:hover {
        transform: scale(1);
    }

    .view-bukti {
        transition: all 0.2s ease;
    }

    .view-bukti:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .print-only {
        display: none;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Back button and actions -->
            <div class="mb-4 no-print">
                <a href="<?= site_url('admin/booking') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="<?= site_url('admin/booking/edit/' . $booking['kdbooking']) ?>" class="btn btn-warning ms-2">
                    <i class="bi bi-pencil-square"></i> Edit Booking
                </a>
                <button class="btn btn-primary ms-2" id="btnPrintInvoice">
                    <i class="bi bi-printer"></i> Cetak Faktur
                </button>

                <!-- Status update dropdown for admin -->
                <div class="dropdown d-inline-block ms-2">
                    <button class="btn btn-info dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-exchange-alt"></i> Ubah Status
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                        <li><a class="dropdown-item status-action" href="#" data-status="pending">Pending</a></li>
                        <li><a class="dropdown-item status-action" href="#" data-status="confirmed">Konfirmasi</a></li>
                        <li><a class="dropdown-item status-action" href="#" data-status="completed">Selesai</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item status-action" href="#" data-status="cancelled">Batalkan</a></li>
                        <li><a class="dropdown-item status-action" href="#" data-status="no-show">Tidak Hadir</a></li>
                        <li><a class="dropdown-item status-action" href="#" data-status="rejected">Tolak</a></li>
                    </ul>
                </div>
            </div>

            <!-- Faktur/Invoice -->
            <div class="booking-detail-container" id="invoice">
                <div class="invoice-header">
                    <div>
                        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Awan Barbershop" onerror="this.src='https://via.placeholder.com/100x50?text=LOGO'">
                        <h4>AWAN BARBERSHOP</h4>
                        <p class="mb-0">Jl. Contoh No. 123, Kota</p>
                        <p class="mb-0">Telp: 081234567890</p>
                    </div>
                    <div>
                        <div class="invoice-title">FAKTUR BOOKING</div>
                        <div class="invoice-number">#<?= $booking['kdbooking'] ?></div>
                        <div class="invoice-date">Tanggal: <?= date('d/m/Y', strtotime($booking['created_at'])) ?></div>
                    </div>
                </div>

                <div class="row">
                    <!-- Informasi Booking -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="card-header">
                                <h5><i class="fas fa-info-circle"></i> Informasi Booking</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless detail-table">
                                    <tr>
                                        <th>ID Booking</th>
                                        <td><?= $booking['kdbooking'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Booking</th>
                                        <td><?= date('d F Y', strtotime($booking['tanggal_booking'])) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="booking-status status-<?= $booking['status'] ?>">
                                                <?php
                                                $statusMap = [
                                                    'pending' => 'Menunggu Konfirmasi',
                                                    'confirmed' => 'Terkonfirmasi',
                                                    'completed' => 'Selesai',
                                                    'cancelled' => 'Dibatalkan',
                                                    'no-show' => 'Tidak Hadir',
                                                    'rejected' => 'Ditolak'
                                                ];
                                                echo $statusMap[$booking['status']] ?? $booking['status'];
                                                ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Pembayaran</th>
                                        <td><?= $booking['jenispembayaran'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pelanggan -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="card-header">
                                <h5><i class="fas fa-user"></i> Informasi Pelanggan</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless detail-table">
                                    <tr>
                                        <th>Nama</th>
                                        <td><?= $booking['nama_lengkap'] ?? 'Data tidak tersedia' ?></td>
                                    </tr>
                                    <tr>
                                        <th>No. HP</th>
                                        <td><?= $booking['no_hp'] ?? 'Data tidak tersedia' ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td><?= $booking['email'] ?? 'Data tidak tersedia' ?></td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td><?= $booking['alamat'] ?? 'Data tidak tersedia' ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Layanan -->
                <div class="info-card">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> Detail Layanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered service-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Paket</th>
                                        <th>Deskripsi</th>
                                        <th>Waktu</th>
                                        <th>Karyawan</th>
                                        <th class="text-end">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0;
                                    $counter = 1; ?>
                                    <?php foreach ($details as $detail): ?>
                                        <tr>
                                            <td><?= $counter++ ?></td>
                                            <td><?= $detail['nama_paket'] ?></td>
                                            <td><?= $detail['deskripsi'] ?></td>
                                            <td><?= $detail['jamstart'] ?> - <?= $detail['jamend'] ?></td>
                                            <td>
                                                <?php
                                                $karyawanModel = new \App\Models\KaryawanModel();
                                                $karyawan = $karyawanModel->find($detail['idkaryawan']);
                                                echo $karyawan ? $karyawan['namakaryawan'] : 'Belum ditentukan';
                                                ?>
                                            </td>
                                            <td class="text-end">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                        </tr>
                                        <?php $total += $detail['harga']; ?>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Total</th>
                                        <th class="text-end">Rp <?= number_format($booking['total'], 0, ',', '.') ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-end">Jumlah Bayar</th>
                                        <th class="text-end">Rp <?= number_format($booking['jumlahbayar'], 0, ',', '.') ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="5" class="text-end">Sisa</th>
                                        <th class="text-end">Rp <?= number_format($booking['total'] - $booking['jumlahbayar'], 0, ',', '.') ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Payment History -->
                        <?php if (!empty($pembayaran) && is_array($pembayaran)): ?>
                            <div class="payment-info">
                                <h6>Riwayat Pembayaran</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Metode</th>
                                                <th>Status</th>
                                                <th>Jenis</th>
                                                <th class="text-end">Jumlah</th>
                                                <th>Bukti</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pembayaran as $bayar): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y H:i', strtotime($bayar['created_at'])) ?></td>
                                                    <td><?= ucfirst($bayar['metode']) ?></td>
                                                    <td>
                                                        <span class="badge <?= $bayar['status'] == 'paid' ? 'bg-success' : 'bg-warning' ?>">
                                                            <?= $bayar['status'] == 'paid' ? 'Dibayar' : 'Belum Dibayar' ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?= ($bayar['jenis'] ?? '') == 'DP' ? 'bg-warning' : 'bg-info' ?>">
                                                            <?= ($bayar['jenis'] ?? 'Lunas') ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-end">Rp <?= number_format($bayar['total_bayar'], 0, ',', '.') ?></td>
                                                    <td>
                                                        <?php if (!empty($bayar['bukti'])): ?>
                                                            <button type="button" class="btn btn-sm btn-primary view-bukti no-print"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#buktiModal"
                                                                data-bukti="<?= base_url('uploads/bukti_pembayaran/' . $bayar['bukti']) ?>"
                                                                data-id="<?= $bayar['id'] ?>">
                                                                Lihat Bukti
                                                            </button>
                                                            <span class="d-none print-only">Ada</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Tidak ada</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Footer -->
                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <h6>Catatan:</h6>
                            <p>1. Harap datang 10 menit sebelum waktu yang dijadwalkan.</p>
                            <p>2. Pembatalan harus dilakukan minimal 2 jam sebelum jadwal.</p>
                            <p>3. Faktur ini sebagai bukti sah pembayaran.</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <p>Terima kasih atas kunjungan Anda!</p>
                        <p class="mb-0">AWAN BARBERSHOP</p>
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
        // Handle status update
        $('.status-action').on('click', function(e) {
            e.preventDefault();

            const status = $(this).data('status');
            const kdbooking = '<?= $booking['kdbooking'] ?>';

            // Jika status completed, cek sisa pembayaran dulu
            if (status === 'completed') {
                // Cek apakah ada sisa pembayaran
                $.ajax({
                    url: '<?= site_url('admin/booking/getPaymentInfo') ?>',
                    type: 'POST',
                    data: {
                        kdbooking: kdbooking
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            const data = response.data;

                            // Set nilai kdbooking dan status pada modal
                            $('#kdbooking').val(kdbooking);
                            $('#status').val(status);

                            // Jika ada sisa pembayaran, tampilkan form pelunasan
                            if (parseFloat(data.sisa) > 0) {
                                $('#booking_id_pembayaran').val(kdbooking);
                                $('#sisa_pembayaran').val(data.sisa);

                                // Format currency
                                $('#totalBooking').text('Rp ' + formatRupiah(data.total));
                                $('#sudahDibayar').text('Rp ' + formatRupiah(data.jumlahbayar));
                                $('#sisaPembayaran').text('Rp ' + formatRupiah(data.sisa));

                                // Tampilkan info sisa pembayaran
                                $('#sisaPembayaranInfo').show();
                                $('#formPelunasan').show();
                            } else {
                                $('#sisaPembayaranInfo').hide();
                                $('#formPelunasan').hide();
                            }

                            // Tampilkan modal status
                            $('#statusModal').modal('show');
                        } else {
                            console.error("Error fetching payment info:", response.message);
                            showStatusConfirmation(kdbooking, status);
                        }
                    },
                    error: function() {
                        console.error("AJAX Error fetching payment info");
                        showStatusConfirmation(kdbooking, status);
                    }
                });
            } else {
                // Untuk status selain completed, langsung tampilkan modal
                $('#kdbooking').val(kdbooking);
                $('#status').val(status);
                $('#sisaPembayaranInfo').hide();
                $('#formPelunasan').hide();
                $('#statusModal').modal('show');
            }
        });

        // Helper function untuk menampilkan konfirmasi status
        function showStatusConfirmation(kdbooking, status) {
            $('#kdbooking').val(kdbooking);
            $('#status').val(status);
            $('#sisaPembayaranInfo').hide();
            $('#formPelunasan').hide();
            $('#statusModal').modal('show');
        }

        // Handle form status submit
        $('#statusForm').on('submit', function(e) {
            e.preventDefault();

            const kdbooking = $('#kdbooking').val();
            const status = $('#status').val();

            // Data untuk update status
            let data = {
                kdbooking: kdbooking,
                status: status,
                update_payment: (status === 'completed' || status === 'cancelled' || status === 'rejected')
            };

            // Tambahkan data pelunasan jika status completed dan pelunasan dicentang
            if (status === 'completed' && $('#formPelunasan').is(':visible') && $('#lunaskan').is(':checked')) {
                data.pelunasan = true;
                data.metode_pembayaran = $('#metode_pembayaran').val();
                data.jumlah_pembayaran = $('#sisa_pembayaran').val();
            }

            // Tutup modal
            $('#statusModal').modal('hide');

            // Kirim request update status
            $.ajax({
                url: '<?= site_url('admin/booking/updateStatus') ?>',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Status berhasil diperbarui',
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal memperbarui status: ' + response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memperbarui status'
                    });
                }
            });
        });

        // Helper function untuk format angka ke format Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Handle cetak faktur
        $('#btnPrintInvoice').on('click', function() {
            cetakFaktur();
        });

        // Handle tampilan bukti pembayaran
        $('.view-bukti').on('click', function() {
            var buktiUrl = $(this).data('bukti');
            var id = $(this).data('id');

            $('#buktiImage').attr('src', buktiUrl);
            $('#buktiModalLabel').text('Bukti Pembayaran #' + id);
            $('#downloadBukti').attr('href', buktiUrl);

            // Reset zoom state
            $('#buktiImage').css('max-height', '500px').removeClass('zoomed');
            $('#zoomBukti').find('span').text('Perbesar');
            $('#zoomBukti').find('i').removeClass('bi-zoom-out').addClass('bi-zoom-in');

            // Preload gambar
            var img = new Image();
            img.onload = function() {
                // Gambar berhasil dimuat
                $('#buktiImage').removeClass('d-none');
            };
            img.onerror = function() {
                // Gambar gagal dimuat
                $('#buktiImage').addClass('d-none');
                $('.modal-body').append('<div class="alert alert-danger">Gambar tidak dapat dimuat</div>');
            };
            img.src = buktiUrl;
        });

        // Handle zoom gambar
        $('#zoomBukti').on('click', function() {
            var $img = $('#buktiImage');
            var $icon = $(this).find('i');
            var $text = $(this).find('span');

            if ($img.hasClass('zoomed')) {
                // Kecilkan gambar
                $img.css('max-height', '500px').removeClass('zoomed');
                $icon.removeClass('bi-zoom-out').addClass('bi-zoom-in');
                $text.text('Perbesar');
            } else {
                // Perbesar gambar
                $img.css('max-height', 'none').addClass('zoomed');
                $icon.removeClass('bi-zoom-in').addClass('bi-zoom-out');
                $text.text('Kecilkan');
            }
        });

        // Reset modal saat ditutup
        $('#buktiModal').on('hidden.bs.modal', function() {
            $('#buktiImage').attr('src', '').css('max-height', '500px').removeClass('zoomed');
            $('.alert').remove();
        });
    });

    // Fungsi untuk mencetak faktur ke halaman baru
    function cetakFaktur() {
        try {
            var invoiceContent = document.getElementById('invoice').innerHTML;
            var printWindow = window.open('', '_blank', 'height=600,width=800');

            if (!printWindow) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Popup blocker mungkin menghalangi jendela cetak. Mohon izinkan popup untuk situs ini.'
                });
                return;
            }

            printWindow.document.write('<!DOCTYPE html>');
            printWindow.document.write('<html lang="id">');
            printWindow.document.write('<head>');
            printWindow.document.write('<meta charset="UTF-8">');
            printWindow.document.write('<meta name="viewport" content="width=device-width, initial-scale=1.0">');
            printWindow.document.write('<title>Faktur Booking - <?= $booking['kdbooking'] ?></title>');
            printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; margin: 0; padding: 10px; font-size: 12px; }');
            printWindow.document.write('.container { max-width: 100%; padding: 0; }');
            printWindow.document.write('.booking-detail-container { max-width: 100%; margin: 0 auto; }');
            printWindow.document.write('.row { margin-right: -5px; margin-left: -5px; }');
            printWindow.document.write('.col-md-6, .col-md-8, .col-md-4, .col-12 { padding-right: 5px; padding-left: 5px; }');
            printWindow.document.write('.info-card { margin-bottom: 10px; border-radius: 5px; overflow: hidden; box-shadow: 0 0 5px rgba(0,0,0,0.1); }');
            printWindow.document.write('.info-card .card-header { background-color: #f8f9fa; border-bottom: 1px solid #e9ecef; padding: 8px 10px; }');
            printWindow.document.write('.info-card .card-header h5 { margin: 0; color: #495057; font-weight: 600; font-size: 14px; }');
            printWindow.document.write('.info-card .card-body { padding: 10px; }');
            printWindow.document.write('.booking-id { font-size: 18px; font-weight: 700; color: #007bff; margin-bottom: 3px; }');
            printWindow.document.write('.booking-date { font-size: 12px; color: #6c757d; margin-bottom: 10px; }');
            printWindow.document.write('.booking-status { display: inline-block; padding: 3px 8px; border-radius: 15px; font-weight: 500; font-size: 11px; text-transform: uppercase; }');
            printWindow.document.write('.status-pending { background-color: #ffc107; color: #212529; }');
            printWindow.document.write('.status-confirmed { background-color: #0dcaf0; color: #fff; }');
            printWindow.document.write('.status-completed { background-color: #198754; color: #fff; }');
            printWindow.document.write('.status-cancelled { background-color: #dc3545; color: #fff; }');
            printWindow.document.write('.status-no-show { background-color: #6c757d; color: #fff; }');
            printWindow.document.write('.status-rejected { background-color: #dc3545; color: #fff; }');
            printWindow.document.write('.detail-table { margin-bottom: 0; }');
            printWindow.document.write('.detail-table th, .detail-table td { padding: 4px; font-size: 12px; }');
            printWindow.document.write('.detail-table th { width: 35%; font-weight: 600; }');
            printWindow.document.write('.service-table { margin-bottom: 5px; }');
            printWindow.document.write('.service-table th, .service-table td { padding: 5px; vertical-align: middle; font-size: 11px; }');
            printWindow.document.write('.service-table th { font-size: 11px; }');
            printWindow.document.write('.table>:not(caption)>*>* { padding: 5px; }');
            printWindow.document.write('.payment-info { border-top: 1px solid #dee2e6; margin-top: 10px; padding-top: 10px; }');
            printWindow.document.write('.payment-info h6 { font-weight: 600; margin-bottom: 8px; font-size: 13px; }');
            printWindow.document.write('.total-section { font-size: 14px; font-weight: 700; }');
            printWindow.document.write('.invoice-header { margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }');
            printWindow.document.write('.invoice-header img { max-width: 70px; height: auto; }');
            printWindow.document.write('.invoice-header h4 { font-size: 16px; margin-bottom: 2px; }');
            printWindow.document.write('.invoice-header p { font-size: 11px; margin-bottom: 2px; }');
            printWindow.document.write('.invoice-title { font-size: 18px; font-weight: 700; color: #212529; margin-bottom: 2px; text-align: right; }');
            printWindow.document.write('.invoice-number { font-size: 13px; color: #6c757d; text-align: right; }');
            printWindow.document.write('.mt-4 { margin-top: 10px !important; }');
            printWindow.document.write('.mb-4 { margin-bottom: 10px !important; }');
            printWindow.document.write('p { margin-bottom: 3px; font-size: 11px; }');
            printWindow.document.write('h6 { font-size: 13px; margin-bottom: 5px; }');
            printWindow.document.write('.print-only { display: none; }');
            printWindow.document.write('@media print { body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; } .no-print { display: none !important; } .print-only { display: inline-block !important; } @page { size: A4 portrait; margin: 5mm; } }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head>');
            printWindow.document.write('<body>');
            printWindow.document.write('<div class="container">');
            printWindow.document.write(invoiceContent);
            printWindow.document.write('</div>');
            printWindow.document.write('<script>');
            printWindow.document.write('window.onload = function() {');
            printWindow.document.write('  setTimeout(function() { ');
            printWindow.document.write('    window.print();');
            printWindow.document.write('    // Tambahkan listener untuk deteksi selesai mencetak');
            printWindow.document.write('    window.addEventListener("afterprint", function() {');
            printWindow.document.write('      // Tanya pengguna apakah ingin menutup jendela setelah mencetak');
            printWindow.document.write('      if(confirm("Apakah Anda ingin menutup jendela faktur?")) {');
            printWindow.document.write('        window.close();');
            printWindow.document.write('      }');
            printWindow.document.write('    });');
            printWindow.document.write('  }, 1000);');
            printWindow.document.write('};');
            printWindow.document.write('<\/script>');
            printWindow.document.write('</body>');
            printWindow.document.write('</html>');

            printWindow.document.close();
            printWindow.focus();

        } catch (error) {
            console.error('Error cetak faktur:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat mencetak faktur: ' + error.message
            });
        }
    }
</script>
<!-- Modal Bukti Pembayaran -->
<div class="modal fade" id="buktiModal" tabindex="-1" aria-labelledby="buktiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buktiModalLabel">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="buktiImage" src="" alt="Bukti Pembayaran" class="img-fluid" style="max-height: 500px;">
                <div class="mt-3">
                    <a href="#" id="downloadBukti" class="btn btn-sm btn-success" download>
                        <i class="bi bi-download"></i> Download Gambar
                    </a>
                    <button type="button" id="zoomBukti" class="btn btn-sm btn-info">
                        <i class="bi bi-zoom-in"></i> <span>Perbesar</span>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Status -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">
                    <i class="bi bi-arrow-repeat me-2"></i> Ubah Status Booking
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <input type="hidden" id="kdbooking" name="kdbooking">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending">Menunggu Konfirmasi</option>
                            <option value="confirmed">Terkonfirmasi</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                            <option value="no-show">Tidak Hadir</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>

                    <!-- Informasi sisa pembayaran -->
                    <div id="sisaPembayaranInfo" class="mt-3" style="display: none;">
                        <div class="alert alert-info">
                            <h6 class="mb-2"><i class="bi bi-info-circle"></i> Informasi Pembayaran</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td>Total</td>
                                        <td>:</td>
                                        <td class="text-end" id="totalBooking">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td>Sudah Dibayar</td>
                                        <td>:</td>
                                        <td class="text-end" id="sudahDibayar">Rp 0</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td>Sisa</td>
                                        <td>:</td>
                                        <td class="text-end" id="sisaPembayaran">Rp 0</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Form pelunasan -->
                        <div id="formPelunasan" class="mt-3">
                            <h6 class="mb-2">Pelunasan Pembayaran</h6>
                            <input type="hidden" id="booking_id_pembayaran" name="booking_id_pembayaran">
                            <input type="hidden" id="sisa_pembayaran" name="sisa_pembayaran">

                            <div class="form-group mb-2">
                                <label for="metode_pembayaran">Metode Pembayaran</label>
                                <select class="form-control" id="metode_pembayaran" name="metode_pembayaran">
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lunaskan" name="lunaskan" value="1" checked>
                                    <label class="custom-control-label" for="lunaskan">Lunaskan pembayaran</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>