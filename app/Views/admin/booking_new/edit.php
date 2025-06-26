<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .booking-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .booking-card {
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .booking-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        padding: 15px 20px;
    }

    .booking-card .card-header h5 {
        margin: 0;
        color: #495057;
        font-weight: 600;
    }

    .booking-card .card-body {
        padding: 20px;
    }

    /* Styling untuk pemilihan jam */
    .time-slots-container {
        margin-top: 20px;
    }

    .time-slot {
        display: block;
        width: 100%;
        padding: 10px 15px;
        margin-bottom: 5px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        background-color: #fff;
        color: #212529;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .time-slot:hover {
        background-color: #f8f9fa;
        border-color: #0d6efd;
    }

    .time-slot.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }

    .time-slot.booked {
        background-color: #dc3545;
        color: white;
        border-color: #dc3545;
        cursor: not-allowed;
    }

    .time-slot.partial-booked {
        background-color: #ffc107;
        color: #212529;
        border-color: #ffc107;
        cursor: pointer;
    }

    .time-slot.disabled {
        background-color: #f8f9fa;
        color: #6c757d;
        border-color: #dee2e6;
        cursor: not-allowed;
    }

    .booking-date-display {
        font-weight: bold;
        color: #495057;
        margin-bottom: 15px;
    }

    .status-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        margin-right: 15px;
    }

    .legend-color {
        width: 15px;
        height: 15px;
        border-radius: 3px;
        margin-right: 5px;
    }

    .date-picker-container {
        position: relative;
    }

    .date-picker-container .form-control {
        background-color: #fff;
        border: 1px solid #ced4da;
        padding-left: 40px;
        height: calc(2.5rem + 2px);
        font-size: 1rem;
    }

    .date-picker-container .calendar-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #0d6efd;
        z-index: 4;
    }

    .form-section-title {
        font-weight: 600;
        color: #495057;
        margin-top: 20px;
        margin-bottom: 15px;
        padding-bottom: 5px;
        border-bottom: 1px solid #e9ecef;
    }

    .customer-info-display {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .customer-info-display p {
        margin-bottom: 8px;
    }

    .customer-info-display strong {
        color: #495057;
    }

    .switch-container {
        margin-bottom: 15px;
    }

    .form-switch {
        padding-left: 2.5em;
    }

    .form-switch .form-check-input {
        width: 2em;
    }

    .form-check-label {
        font-weight: 500;
    }

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 5;
    }

    .legend-available {
        background-color: #fff;
        border: 1px solid #dee2e6;
    }

    .legend-selected {
        background-color: #0d6efd;
    }

    .legend-booked {
        background-color: #dc3545;
    }

    .legend-partial {
        background-color: #ffc107;
    }

    .legend-disabled {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="booking-container">
        <form id="editBookingForm">
            <input type="hidden" name="kdbooking" value="<?= $booking['kdbooking'] ?>">
            <input type="hidden" name="idpelanggan" value="<?= $booking['idpelanggan'] ?>">

            <div class="row">
                <div class="col-md-8">
                    <!-- Pelanggan Info Card -->
                    <div class="card booking-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5><i class="bi bi-person"></i> Informasi Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            <div class="customer-info-display">
                                <p><strong>Nama:</strong> <?= $booking['nama_lengkap'] ?></p>
                                <p><strong>No. HP:</strong> <?= $booking['no_hp'] ?></p>
                                <p><strong>Email:</strong> <?= $booking['email'] ?></p>
                                <p><strong>Alamat:</strong> <?= $booking['alamat'] ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Booking Card -->
                    <div class="card booking-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5><i class="bi bi-calendar-check"></i> Detail Booking</h5>
                            <div class="form-check form-switch switch-container">
                                <input class="form-check-input" type="checkbox" id="editDetailsSwitch" name="update_details" value="yes">
                                <label class="form-check-label" for="editDetailsSwitch">Edit Detail</label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="detailDisplaySection">
                                <?php foreach ($details as $detail): ?>
                                    <div class="mb-3 p-3 border rounded">
                                        <p><strong>Paket:</strong> <?= $detail['nama_paket'] ?></p>
                                        <p><strong>Deskripsi:</strong> <?= $detail['deskripsi'] ?></p>
                                        <p><strong>Tanggal:</strong> <?= date('d F Y', strtotime($detail['tgl'])) ?></p>
                                        <p><strong>Jam:</strong> <?= $detail['jamstart'] ?> - <?= $detail['jamend'] ?></p>
                                        <p><strong>Harga:</strong> Rp <?= number_format($detail['harga'], 0, ',', '.') ?></p>
                                        <?php
                                        $karyawanModel = new \App\Models\KaryawanModel();
                                        $karyawan = $karyawanModel->find($detail['idkaryawan']);
                                        ?>
                                        <p><strong>Karyawan:</strong> <?= $karyawan ? $karyawan['namakaryawan'] : 'Belum ditentukan' ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div id="detailEditSection" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="tanggal_booking" class="form-label">Tanggal Booking</label>
                                    <div class="date-picker-container">
                                        <i class="bi bi-calendar calendar-icon"></i>
                                        <input type="date" class="form-control" id="tanggal_booking" name="tanggal_booking" value="<?= $booking['tanggal_booking'] ?>" min="<?= date('Y-m-d') ?>">
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Jam</label>
                                    <?php
                                    $jamstart = '';
                                    $jamend = '';
                                    if (!empty($details) && isset($details[0])) {
                                        $jamstart = $details[0]['jamstart'] ?? '';
                                        $jamend = $details[0]['jamend'] ?? '';
                                    }
                                    ?>
                                    <input type="hidden" id="jamstart" name="jamstart" value="<?= $jamstart ?>">
                                    <input type="hidden" id="jamend" name="jamend" value="<?= $jamend ?>">

                                    <div class="booking-date-display" id="bookingDateDisplay">
                                        <?php if (!empty($jamstart) && !empty($jamend)): ?>
                                            <i class="bi bi-info-circle"></i> Jam saat ini: <?= $jamstart ?> - <?= $jamend ?>
                                        <?php else: ?>
                                            <i class="bi bi-info-circle"></i> Silakan pilih jam booking
                                        <?php endif; ?>
                                    </div>

                                    <div id="timeSlotContainer" class="time-slots-container">
                                        <div class="time-slot" data-time="09:00">09:00</div>
                                        <div class="time-slot" data-time="10:00">10:00</div>
                                        <div class="time-slot" data-time="11:00">11:00</div>
                                        <div class="time-slot" data-time="12:00">12:00</div>
                                        <div class="time-slot" data-time="13:00">13:00</div>
                                        <div class="time-slot" data-time="14:00">14:00</div>
                                        <div class="time-slot" data-time="15:00">15:00</div>
                                        <div class="time-slot" data-time="16:00">16:00</div>
                                        <div class="time-slot" data-time="17:00">17:00</div>
                                        <div class="time-slot" data-time="18:00">18:00</div>
                                        <div class="time-slot" data-time="19:00">19:00</div>
                                        <div class="time-slot" data-time="20:00">20:00</div>
                                        <div class="time-slot" data-time="21:00">21:00</div>
                                    </div>

                                    <div class="status-legend mt-3">
                                        <div class="legend-item">
                                            <div class="legend-color legend-available"></div>
                                            <span>Tersedia</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color legend-selected"></div>
                                            <span>Dipilih</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color legend-booked"></div>
                                            <span>Penuh</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color legend-partial"></div>
                                            <span>Sebagian</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color legend-disabled"></div>
                                            <span>Tidak tersedia</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="idpaket">Paket</label>
                                    <div class="selected-paket">
                                        <div class="paket-card mb-2">
                                            <?php
                                            $selectedPaket = null;
                                            foreach ($paketList as $paket) {
                                                if (!empty($details) && isset($details[0]) && isset($details[0]['kdpaket']) && $details[0]['kdpaket'] == $paket['idpaket']) {
                                                    $selectedPaket = $paket;
                                                    break;
                                                }
                                            }
                                            // Jika tidak ditemukan, coba cari berdasarkan $booking['idpaket'] jika ada
                                            if (!$selectedPaket && isset($booking['kdpaket'])) {
                                                foreach ($paketList as $paket) {
                                                    if ($booking['kdpaket'] == $paket['idpaket']) {
                                                        $selectedPaket = $paket;
                                                        break;
                                                    }
                                                }
                                            }
                                            ?>
                                            <div id="selectedPaketInfo">
                                                <?php if ($selectedPaket): ?>
                                                    <div class="card border-0 bg-light">
                                                        <div class="card-body p-3">
                                                            <h6 class="card-title mb-2"><i class="bi bi-list text-primary"></i> <strong><?= $selectedPaket['namapaket'] ?></strong></h6>
                                                            <p class="card-text mb-1"><?= $selectedPaket['deskripsi'] ?></p>
                                                            <p class="card-text mb-0"><strong>Harga:</strong> Rp <?= number_format($selectedPaket['harga'], 0, ',', '.') ?></p>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="alert alert-warning">
                                                        <i class="bi bi-exclamation-triangle"></i> Paket tidak ditemukan. Silakan klik tombol "Pilih Paket" di bawah.
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="hidden" id="idpaket" name="kdpaket" value="<?= $selectedPaket ? $selectedPaket['idpaket'] : (isset($booking['kdpaket']) ? $booking['kdpaket'] : (isset($details[0]['kdpaket']) ? $details[0]['kdpaket'] : '')) ?>">
                                            <input type="hidden" id="total" name="total" value="<?= $selectedPaket ? $selectedPaket['harga'] : $booking['total'] ?>">
                                        </div>
                                        <a href="javascript:void(0);" class="btn btn-primary btn-sm" id="btnSearchPaket">
                                            <i class="bi bi-search"></i> Ganti Paket
                                        </a>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="idkaryawan">Karyawan</label>
                                    <div class="selected-karyawan">
                                        <div class="karyawan-card mb-2">
                                            <?php
                                            $selectedKaryawan = null;
                                            if (!empty($details) && isset($details[0]) && isset($details[0]['idkaryawan'])) {
                                                foreach ($karyawanList as $karyawan) {
                                                    if ($details[0]['idkaryawan'] == $karyawan['idkaryawan']) {
                                                        $selectedKaryawan = $karyawan;
                                                        break;
                                                    }
                                                }
                                            }

                                            // Jika tidak ditemukan, coba cari berdasarkan $booking['idkaryawan'] jika ada
                                            if (!$selectedKaryawan && isset($booking['idkaryawan'])) {
                                                foreach ($karyawanList as $karyawan) {
                                                    if ($booking['idkaryawan'] == $karyawan['idkaryawan']) {
                                                        $selectedKaryawan = $karyawan;
                                                        break;
                                                    }
                                                }
                                            }
                                            ?>
                                            <div id="selectedKaryawanInfo">
                                                <?php if ($selectedKaryawan): ?>
                                                    <div class="card border-0 bg-light">
                                                        <div class="card-body p-3">
                                                            <h6 class="card-title mb-0"><i class="bi bi-person text-primary"></i> <strong><?= $selectedKaryawan['namakaryawan'] ?></strong></h6>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="alert alert-warning">
                                                        <i class="bi bi-exclamation-triangle"></i> Karyawan tidak ditemukan. Silakan klik tombol "Pilih Karyawan" di bawah.
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <input type="hidden" id="idkaryawan" name="idkaryawan" value="<?= $selectedKaryawan ? $selectedKaryawan['idkaryawan'] : (isset($booking['idkaryawan']) ? $booking['idkaryawan'] : '') ?>">
                                        </div>
                                        <a href="javascript:void(0);" class="btn btn-primary btn-sm" id="btnSearchKaryawan">
                                            <i class="bi bi-search"></i> Ganti Karyawan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pembayaran Card -->
                    <div class="card booking-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5><i class="bi bi-credit-card"></i> Pembayaran</h5>
                            <div class="form-check form-switch switch-container">
                                <input class="form-check-input" type="checkbox" id="editPaymentSwitch" name="update_payment" value="yes">
                                <label class="form-check-label" for="editPaymentSwitch">Edit Pembayaran</label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="paymentDisplaySection">
                                <p><strong>Status Pembayaran:</strong> <?= $booking['jenispembayaran'] ?></p>
                                <p><strong>Total:</strong> Rp <?= number_format($booking['total'], 0, ',', '.') ?></p>
                                <p><strong>Jumlah Bayar:</strong> Rp <?= number_format($booking['jumlahbayar'], 0, ',', '.') ?></p>
                                <p><strong>Sisa:</strong> Rp <?= number_format($booking['total'] - $booking['jumlahbayar'], 0, ',', '.') ?></p>
                                <?php if ($pembayaran): ?>
                                    <p><strong>Metode Pembayaran:</strong> <?= $pembayaran['metode'] ?></p>
                                <?php endif; ?>
                            </div>

                            <div id="paymentEditSection" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="jenispembayaran">Jenis Pembayaran</label>
                                    <select class="form-control" id="jenispembayaran" name="jenispembayaran">
                                        <option value="DP" <?= $booking['jenispembayaran'] == 'DP' ? 'selected' : '' ?>>DP (50%)</option>
                                        <option value="Lunas" <?= $booking['jenispembayaran'] == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="total">Total</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="total_display" value="<?= $booking['total'] ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="jumlahbayar">Jumlah Bayar</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="jumlahbayar" name="jumlahbayar" value="<?= $booking['jumlahbayar'] ?>">
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="metode_pembayaran">Metode Pembayaran</label>
                                    <select class="form-control" id="metode_pembayaran" name="metode_pembayaran">
                                        <option value="Tunai" <?= ($pembayaran['metode'] ?? '') == 'Tunai' ? 'selected' : '' ?>>Tunai</option>
                                        <option value="Transfer" <?= ($pembayaran['metode'] ?? '') == 'Transfer' ? 'selected' : '' ?>>Transfer</option>
                                        <option value="QRIS" <?= ($pembayaran['metode'] ?? '') == 'QRIS' ? 'selected' : '' ?>>QRIS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Status Card -->
                    <div class="card booking-card">
                        <div class="card-header">
                            <h5><i class="bi bi-info-circle"></i> Status Booking</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="pending" <?= $booking['status'] == 'pending' ? 'selected' : '' ?>>Menunggu Konfirmasi</option>
                                    <option value="confirmed" <?= $booking['status'] == 'confirmed' ? 'selected' : '' ?>>Terkonfirmasi</option>
                                    <option value="completed" <?= $booking['status'] == 'completed' ? 'selected' : '' ?>>Selesai</option>
                                    <option value="cancelled" <?= $booking['status'] == 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                                    <option value="no-show" <?= $booking['status'] == 'no-show' ? 'selected' : '' ?>>Tidak Hadir</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Card -->
                    <div class="card booking-card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" id="btnSubmit">
                                <i class="bi bi-save"></i> Simpan Perubahan
                            </button>
                            <a href="<?= site_url('admin/booking/show/' . $booking['kdbooking']) ?>" class="btn btn-outline-secondary btn-block mt-2">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(function() {
        // Setup toastr options
        toastr.options = {
            closeButton: true,
            newestOnTop: true,
            progressBar: true,
            positionClass: "toast-top-right",
            preventDuplicates: false,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };

        // Toggle edit details section
        $('#editDetailsSwitch').on('change', function() {
            if ($(this).is(':checked')) {
                $('#detailDisplaySection').hide();
                $('#detailEditSection').show();
                checkTimeSlotAvailability();
            } else {
                $('#detailDisplaySection').show();
                $('#detailEditSection').hide();
            }
        });

        // Toggle edit payment section
        $('#editPaymentSwitch').on('change', function() {
            if ($(this).is(':checked')) {
                $('#paymentDisplaySection').hide();
                $('#paymentEditSection').show();

                // Update total dan jumlah bayar
                const totalHarga = parseInt($('#total').val() || 0);
                $('#total_display').val(totalHarga);
                updateJumlahBayar();
            } else {
                $('#paymentDisplaySection').show();
                $('#paymentEditSection').hide();
            }
        });

        // Event ketika tanggal berubah
        $('#tanggal_booking').on('change', function() {
            const selectedDate = $(this).val();

            if (selectedDate) {
                // Format tanggal untuk tampilan
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', options);
                $('#bookingDateDisplay').html(`<strong>${formattedDate}</strong>`);

                // Tampilkan container time slot jika tersembunyi
                $('#timeSlotContainer').fadeIn(500);

                // Reset semua slot waktu
                $('.time-slot').removeClass('active booked disabled partial-booked');
                $('.time-slot').removeAttr('title');

                // Periksa ketersediaan slot waktu
                checkTimeSlotAvailability();
            } else {
                $('#bookingDateDisplay').html(`
                    <i class="bi bi-info-circle"></i> Silakan pilih tanggal terlebih dahulu
                `);

                // Sembunyikan container time slot
                $('#timeSlotContainer').hide();

                // Reset input jam
                $('#jamstart').val('');
                $('#jamend').val('');
            }
        });

        // Handle klik pada time slot
        $(document).on('click', '.time-slot', function() {
            if ($(this).hasClass('booked') || $(this).hasClass('disabled')) {
                return;
            }

            $('.time-slot').removeClass('active');
            $(this).addClass('active');

            // Set jam mulai & selesai
            const startTime = $(this).data('time');
            const [startHour, startMinute] = startTime.split(':');
            const endHour = parseInt(startHour) + 1;
            const endTime = `${endHour.toString().padStart(2, '0')}:${startMinute}`;

            $('#jamstart').val(startTime);
            $('#jamend').val(endTime);

            // Jika karyawan dipilih, cek ketersediaan karyawan
            if ($('#idkaryawan').length) {
                checkAvailableKaryawan();
            }
        });

        // Fungsi untuk memeriksa ketersediaan slot waktu
        function checkTimeSlotAvailability() {
            const tanggalBooking = $('#tanggal_booking').val();
            const kdbooking = $('input[name="kdbooking"]').val();

            if (!tanggalBooking) return;

            // Tambahkan loading indicator
            $('#timeSlotContainer').addClass('position-relative');
            $('#timeSlotContainer').append('<div class="loading-overlay"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');

            $.ajax({
                url: '<?= site_url('admin/booking/check-availability') ?>',
                type: 'GET',
                data: {
                    date: tanggalBooking,
                    exclude_booking: kdbooking // Exclude current booking for edit mode
                },
                dataType: 'json',
                success: function(response) {
                    // Hapus loading indicator
                    $('.loading-overlay').remove();
                    $('#timeSlotContainer').removeClass('position-relative');

                    if (response.success) {
                        const bookedSlots = response.bookedSlots || [];
                        const slotStatus = response.slotStatus || [];
                        const totalKaryawan = response.totalKaryawan || 0;

                        // Tandai slot yang sudah terisi penuh
                        bookedSlots.forEach(function(time) {
                            const timeSlot = $(`.time-slot[data-time="${time}"]`);
                            timeSlot.addClass('booked');
                            timeSlot.attr('title', 'Slot sudah terisi penuh');
                        });

                        // Tandai slot yang terisi sebagian
                        slotStatus.forEach(function(slot) {
                            if (!slot.isFull) {
                                const timeSlot = $(`.time-slot[data-time="${slot.time}"]`);
                                timeSlot.addClass('partial-booked');
                                timeSlot.attr('data-available', slot.available);
                                timeSlot.attr('title', `Tersedia ${slot.available} dari ${totalKaryawan} karyawan - Klik untuk memilih`);
                            }
                        });

                        // Disable slot waktu yang sudah lewat hari ini
                        if (tanggalBooking === '<?= date('Y-m-d') ?>') {
                            const now = new Date();
                            const currentHour = now.getHours();

                            $('.time-slot').each(function() {
                                const slotHour = parseInt($(this).data('time').split(':')[0]);
                                if (slotHour <= currentHour) {
                                    $(this).addClass('disabled');
                                    $(this).attr('title', 'Waktu sudah lewat');
                                }
                            });
                        }

                        // Tambahkan tooltip untuk semua time slot yang masih kosong
                        $('.time-slot:not(.disabled):not(.booked):not(.partial-booked)').attr('title', 'Tersedia - Klik untuk memilih');

                        // Set active class pada jam booking saat ini jika ada
                        const currentStartTime = $('#jamstart').val();
                        if (currentStartTime) {
                            const timeSlot = $(`.time-slot[data-time="${currentStartTime}"]`);
                            if (!timeSlot.hasClass('booked') && !timeSlot.hasClass('disabled')) {
                                timeSlot.addClass('active');
                            }
                        }
                    } else {
                        if (response.message) {
                            toastr.error(response.message);
                        } else {
                            toastr.error('Gagal memuat ketersediaan waktu');
                        }
                    }
                },
                error: function() {
                    // Hapus loading indicator
                    $('.loading-overlay').remove();
                    $('#timeSlotContainer').removeClass('position-relative');
                    toastr.error('Gagal memeriksa ketersediaan slot waktu');
                }
            });
        }

        // Fungsi untuk mengecek karyawan yang tersedia
        function checkAvailableKaryawan() {
            const tanggal = $('#tanggal_booking').val();
            const jamstart = $('#jamstart').val();

            if (tanggal && jamstart) {
                $.ajax({
                    url: '<?= site_url('admin/booking/getAvailableKaryawan') ?>',
                    type: 'GET',
                    data: {
                        tanggal: tanggal,
                        jamstart: jamstart
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            const karyawanList = response.data;

                            // Update opsi karyawan
                            $('#idkaryawan').empty().append('<option value="">-- Pilih Karyawan --</option>');

                            if (karyawanList && karyawanList.length > 0) {
                                $.each(karyawanList, function(i, karyawan) {
                                    $('#idkaryawan').append(`<option value="${karyawan.idkaryawan}">${karyawan.namakaryawan}</option>`);
                                });

                                // Set kembali karyawan yang dipilih sebelumnya jika masih tersedia
                                const selectedKaryawan = '<?= isset($details[0]['idkaryawan']) ? $details[0]['idkaryawan'] : '' ?>';
                                if (selectedKaryawan) {
                                    $('#idkaryawan').val(selectedKaryawan);
                                }

                                toastr.success('Daftar karyawan tersedia telah diperbarui');
                            } else {
                                toastr.warning('Tidak ada karyawan yang tersedia pada waktu tersebut');
                            }
                        } else {
                            toastr.error('Gagal mendapatkan data karyawan');
                        }
                    },
                    error: function() {
                        toastr.error('Gagal memuat data karyawan');
                    }
                });
            }
        }

        // Update total saat paket berubah
        $('#idpaket').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const harga = selectedOption.data('harga') || 0;
            $('#total').val(harga);

            updateJumlahBayar();
        });

        // Update jumlah bayar saat jenis pembayaran berubah
        $('#jenispembayaran').on('change', function() {
            updateJumlahBayar();
        });

        function updateJumlahBayar() {
            const total = parseFloat($('#total').val()) || 0;
            const jenisPembayaran = $('#jenispembayaran').val();

            if (jenisPembayaran === 'Lunas') {
                $('#jumlahbayar').val(total);
            } else if (jenisPembayaran === 'DP') {
                $('#jumlahbayar').val(total * 0.5);
            }
        }

        // Form submission
        $('#editBookingForm').on('submit', function(e) {
            e.preventDefault();

            // Validasi
            if ($('#editDetailsSwitch').is(':checked')) {
                if (!$('#tanggal_booking').val()) {
                    toastr.error('Silakan pilih tanggal booking');
                    return false;
                }

                if (!$('#jamstart').val()) {
                    toastr.error('Silakan pilih jam booking');
                    return false;
                }

                // Validasi paket (tetap menggunakan ID form yang sama meskipun name sudah diubah)
                if (!$('#idpaket').val()) {
                    // Coba ambil nilai dari URL parameter atau data booking
                    const urlParams = new URLSearchParams(window.location.search);
                    const paketIdFromUrl = urlParams.get('idpaket') || urlParams.get('kdpaket');
                    const paketIdFromBooking = '<?= isset($booking["kdpaket"]) ? $booking["kdpaket"] : '' ?>';

                    if (paketIdFromUrl || paketIdFromBooking) {
                        // Jika ada nilai paket dari URL atau data booking, gunakan nilai tersebut
                        $('#idpaket').val(paketIdFromUrl || paketIdFromBooking);
                        console.log('Menggunakan paket ID dari sumber alternatif:', $('#idpaket').val());
                    } else {
                        // Jika tidak ada nilai paket, tampilkan error
                        toastr.error('Silakan pilih paket');
                        return false;
                    }
                }
            }

            if ($('#editPaymentSwitch').is(':checked')) {
                if (!$('#jenispembayaran').val()) {
                    toastr.error('Silakan pilih jenis pembayaran');
                    return false;
                }

                if (!$('#jumlahbayar').val()) {
                    toastr.error('Silakan masukkan jumlah pembayaran');
                    return false;
                }
            }

            // Disable submit button
            $('#btnSubmit').prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Menyimpan...
            `);

            // Submit form
            let formData = $(this).serialize();
            console.log('Form data:', formData); // Debug log

            // Periksa apakah kdpaket ada dalam form data
            if ($('#editDetailsSwitch').is(':checked') && !formData.includes('kdpaket=')) {
                // Tambahkan input hidden tambahan jika kdpaket tidak ada
                $('<input>').attr({
                    type: 'hidden',
                    name: 'kdpaket',
                    value: $('#idpaket').val()
                }).appendTo('#editBookingForm');

                // Update formData setelah menambahkan input baru
                formData = $('#editBookingForm').serialize();
                console.log('Updated form data:', formData);
            }

            $.ajax({
                url: '<?= site_url('admin/booking/update') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.href = '<?= site_url('admin/booking/show/') ?>' + response.kdbooking;
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                        $('#btnSubmit').prop('disabled', false).html('<i class="bi bi-save"></i> Simpan Perubahan');

                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                toastr.error(value);
                            });
                        }
                    }
                },
                error: function() {
                    toastr.error('Terjadi kesalahan saat menyimpan data');
                    $('#btnSubmit').prop('disabled', false).html('<i class="bi bi-save"></i> Simpan Perubahan');
                }
            });
        });

        // Event handler untuk tombol Pilih/Ganti Paket
        $('#btnSearchPaket').on('click', function(e) {
            e.preventDefault();
            const paketModal = new bootstrap.Modal(document.getElementById('paketModal'));
            paketModal.show();
        });

        // Event untuk pencarian paket
        $('#paketSearch').on('keyup', function() {
            const keyword = $(this).val().toLowerCase().trim();

            if (keyword.length > 0) {
                $('#paketTableBody tr').filter(function() {
                    const namaPaket = $(this).find('td:first-child').text().toLowerCase();
                    const deskripsi = $(this).find('td:nth-child(2)').text().toLowerCase();
                    $(this).toggle(namaPaket.indexOf(keyword) > -1 || deskripsi.indexOf(keyword) > -1);
                });

                // Tampilkan pesan jika tidak ada hasil
                if ($('#paketTableBody tr:visible').length === 0) {
                    $('#paketEmpty').show();
                } else {
                    $('#paketEmpty').hide();
                }
            } else {
                $('#paketTableBody tr').show();
                $('#paketEmpty').hide();
            }
        });

        // Event untuk memilih paket dari modal
        $(document).on('click', '.select-paket', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const deskripsi = $(this).data('deskripsi');
            const harga = $(this).data('harga');

            // Set nilai untuk input paket
            $('#idpaket').val(id);

            // Tambahkan log debug
            console.log('Paket terpilih:', {
                id: id,
                nama: nama,
                deskripsi: deskripsi,
                harga: harga,
                inputValue: $('#idpaket').val()
            });

            $('#total').val(harga);
            $('#selectedPaketInfo').html(`
                <div class="card border-0 bg-light">
                    <div class="card-body p-3">
                        <h6 class="card-title mb-2"><i class="bi bi-list text-primary"></i> <strong>${nama}</strong></h6>
                        <p class="card-text mb-1">${deskripsi}</p>
                        <p class="card-text mb-0"><strong>Harga:</strong> Rp ${formatNumber(harga)}</p>
                    </div>
                </div>
            `);

            // Ubah teks tombol menjadi "Ganti Paket"
            $('#btnSearchPaket').html('<i class="bi bi-search"></i> Ganti Paket');

            // Update total display dan jumlah bayar
            $('#total_display').val(harga);
            updateJumlahBayar();

            // Tutup modal
            const paketModal = bootstrap.Modal.getInstance(document.getElementById('paketModal'));
            if (paketModal) paketModal.hide();
        });

        // Event untuk jenis pembayaran
        $('#jenispembayaran').on('change', function() {
            updateJumlahBayar();
        });

        // Event handler untuk tombol Pilih/Ganti Karyawan
        $('#btnSearchKaryawan').on('click', function(e) {
            e.preventDefault();

            // Pastikan ada tanggal dan jam yang dipilih
            if (!$('#tanggal_booking').val() || !$('#jamstart').val()) {
                toastr.warning('Silakan pilih tanggal dan jam booking terlebih dahulu');
                return;
            }

            // Tampilkan loading spinner di dalam modal sebelum membuka modal
            $('#karyawanTableBody').html(`
                <tr>
                    <td colspan="2" class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Memuat data karyawan...</span>
                    </td>
                </tr>
            `);

            // Tampilkan modal
            const karyawanModal = new bootstrap.Modal(document.getElementById('karyawanModal'));
            karyawanModal.show();

            // Muat data karyawan yang tersedia
            const tanggal = $('#tanggal_booking').val();
            const jamstart = $('#jamstart').val();

            $.ajax({
                url: '<?= site_url('admin/booking/getAvailableKaryawan') ?>',
                type: 'GET',
                data: {
                    tanggal: tanggal,
                    jamstart: jamstart
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const karyawanList = response.data;
                        $('#karyawanTableBody').empty();

                        if (karyawanList && karyawanList.length > 0) {
                            $.each(karyawanList, function(i, karyawan) {
                                const row = `
                                    <tr>
                                        <td>${karyawan.namakaryawan}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary select-karyawan" 
                                                data-id="${karyawan.idkaryawan}" 
                                                data-nama="${karyawan.namakaryawan}">
                                                <i class="bi bi-check"></i> Pilih
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                $('#karyawanTableBody').append(row);
                            });
                        } else {
                            $('#karyawanTableBody').html(`
                                <tr>
                                    <td colspan="2" class="text-center py-3">
                                        <i class="bi bi-exclamation-triangle text-warning"></i> 
                                        Tidak ada karyawan yang tersedia pada waktu yang dipilih
                                    </td>
                                </tr>
                            `);
                        }
                    } else {
                        $('#karyawanTableBody').html(`
                            <tr>
                                <td colspan="2" class="text-center py-3">
                                    <i class="bi bi-exclamation-circle text-danger"></i> 
                                    Gagal mendapatkan data karyawan
                                </td>
                            </tr>
                        `);
                    }
                },
                error: function() {
                    $('#karyawanTableBody').html(`
                        <tr>
                            <td colspan="2" class="text-center py-3">
                                <i class="bi bi-exclamation-circle text-danger"></i> 
                                Terjadi kesalahan saat memuat data karyawan
                            </td>
                        </tr>
                    `);
                }
            });
        });

        // Event untuk memilih karyawan dari modal
        $(document).on('click', '.select-karyawan', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            $('#idkaryawan').val(id);
            $('#selectedKaryawanInfo').html(`
                <div class="card border-0 bg-light">
                    <div class="card-body p-3">
                        <h6 class="card-title mb-0"><i class="bi bi-person text-primary"></i> <strong>${nama}</strong></h6>
                    </div>
                </div>
            `);

            // Ubah teks tombol menjadi "Ganti Karyawan"
            $('#btnSearchKaryawan').html('<i class="bi bi-search"></i> Ganti Karyawan');

            // Tutup modal
            const karyawanModal = bootstrap.Modal.getInstance(document.getElementById('karyawanModal'));
            if (karyawanModal) karyawanModal.hide();
        });

        // Format number to rupiah
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        // Fungsi untuk update jumlah bayar berdasarkan jenis pembayaran
        function updateJumlahBayar() {
            const total = parseInt($('#total').val() || 0);
            const jenisPembayaran = $('#jenispembayaran').val();

            if (jenisPembayaran === 'DP') {
                $('#jumlahbayar').val(total / 2);
            } else if (jenisPembayaran === 'Lunas') {
                $('#jumlahbayar').val(total);
            }
        }

        // Inisialisasi status awal
        if ($('#editDetailsSwitch').is(':checked')) {
            $('#detailDisplaySection').hide();
            $('#detailEditSection').show();
            checkTimeSlotAvailability();
        }

        if ($('#editPaymentSwitch').is(':checked')) {
            $('#paymentDisplaySection').hide();
            $('#paymentEditSection').show();
        }
    });
</script>

<!-- Modal Paket -->
<div class="modal fade" id="paketModal" tabindex="-1" aria-labelledby="paketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paketModalLabel">Pilih Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="search-input mb-3">
                    <input type="text" class="form-control" id="paketSearch" placeholder="Cari paket berdasarkan nama...">
                    <span class="search-icon"><i class="bi bi-search"></i></span>
                </div>

                <div class="table-container">
                    <table class="table table-bordered table-sm paket-table">
                        <thead>
                            <tr>
                                <th>Nama Paket</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="paketTableBody">
                            <?php foreach ($paketList as $paket): ?>
                                <tr>
                                    <td><?= $paket['namapaket'] ?></td>
                                    <td><?= $paket['deskripsi'] ?></td>
                                    <td>Rp <?= number_format($paket['harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary select-paket"
                                            data-id="<?= $paket['idpaket'] ?>"
                                            data-nama="<?= $paket['namapaket'] ?>"
                                            data-deskripsi="<?= $paket['deskripsi'] ?>"
                                            data-harga="<?= $paket['harga'] ?>">
                                            <i class="bi bi-check"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div id="paketEmpty" class="text-center py-3" style="display: none;">
                        <span class="text-muted">Tidak ada paket yang ditemukan</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Karyawan -->
<div class="modal fade" id="karyawanModal" tabindex="-1" aria-labelledby="karyawanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="karyawanModalLabel">Pilih Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-container">
                    <table class="table table-bordered table-sm karyawan-table">
                        <thead>
                            <tr>
                                <th>Nama Karyawan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="karyawanTableBody">
                            <!-- Data karyawan akan diisi melalui AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>