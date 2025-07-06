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
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        border: none;
        overflow: hidden;
    }

    .booking-card .card-header {
        background: linear-gradient(to right, #4e73df, #224abe);
        color: white;
        border-bottom: none;
        padding: 15px 20px;
        font-weight: 600;
    }

    .booking-card .card-body {
        padding: 20px;
        background-color: #fff;
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
        border-color: #4e73df;
    }

    .time-slot.active {
        background-color: #4e73df;
        color: white;
        border-color: #4e73df;
    }

    .time-slot.booked {
        background-color: #e74a3b;
        color: white;
        border-color: #e74a3b;
        cursor: not-allowed;
    }

    .time-slot.partial-booked {
        background-color: #f6c23e;
        color: #212529;
        border-color: #f6c23e;
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

    .legend-available {
        background-color: #fff;
        border: 1px solid #dee2e6;
    }

    .legend-selected {
        background-color: #4e73df;
    }

    .legend-booked {
        background-color: #e74a3b;
    }

    .legend-partial {
        background-color: #f6c23e;
    }

    .legend-disabled {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    /* Styling untuk tanggal booking */
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
        color: #4e73df;
        z-index: 4;
    }

    input[type="date"] {
        cursor: pointer;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0;
        cursor: pointer;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* Loading overlay */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
        border-radius: 10px;
    }

    .customer-card,
    .paket-card {
        border: 1px solid #e3e6f0;
        border-radius: 5px;
        padding: 15px;
        margin-top: 10px;
        background-color: #f8f9fc;
    }

    .customer-card h6,
    .paket-card h6 {
        margin-top: 0;
        color: #4e73df;
        font-weight: 600;
    }

    .search-input {
        position: relative;
    }

    .search-input .form-control {
        padding-right: 40px;
    }

    .search-input .search-icon {
        position: absolute;
        right: 10px;
        top: 10px;
        color: #6c757d;
    }

    .table-container {
        max-height: 300px;
        overflow-y: auto;
        border-radius: 5px;
        border: 1px solid #e3e6f0;
    }

    .customer-table tr,
    .paket-table tr {
        cursor: pointer;
    }

    .customer-table tr:hover,
    .paket-table tr:hover {
        background-color: #f1f3ff;
    }

    .selected-customer,
    .selected-paket {
        display: none;
    }

    .btn-action {
        min-width: 120px;
    }

    .payment-details {
        display: none;
    }

    /* Styling untuk paket item */
    .paket-item {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid #e3e6f0;
    }

    .paket-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .selected-paket-item {
        border-left: 4px solid #4e73df;
    }

    /* Responsivitas */
    @media (max-width: 576px) {
        .time-slot {
            padding: 8px 12px;
            font-size: 0.9rem;
        }
    }

    /* Page title styling */
    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-header h1 {
        font-size: 1.75rem;
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 0.5rem;
    }

    .page-header .breadcrumb {
        background: none;
        padding: 0;
        margin: 0;
    }

    .page-header .breadcrumb-item {
        font-size: 0.85rem;
    }

    .page-header .breadcrumb-item.active {
        color: #858796;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="page-header d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-plus mr-2"></i> Tambah Booking Baru
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/booking') ?>">Booking</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Booking</li>
            </ol>
        </nav>
    </div>

    <div class="booking-container">
        <form id="bookingForm">
            <div class="row">
                <div class="col-md-8">
                    <!-- Pelanggan Card -->
                    <div class="card booking-card">
                        <div class="card-header">
                            <h5><i class="fas fa-user mr-2"></i> Informasi Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            <div class="search-customer">
                                <!-- Button untuk membuka modal -->
                                <a href="javascript:void(0);" class="btn btn-primary mb-3" id="btnSearchCustomer">
                                    <i class="fas fa-search mr-1"></i> Cari Pelanggan
                                </a>

                                <div class="selected-customer">
                                    <div class="customer-card">
                                        <h6>Pelanggan Terpilih</h6>
                                        <div id="selectedCustomerInfo">
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle mr-2"></i> Belum ada pelanggan yang dipilih. Silakan klik tombol "Cari Pelanggan" di atas.
                                            </div>
                                        </div>
                                        <input type="hidden" id="idpelanggan" name="idpelanggan">

                                        <!-- Tombol untuk membuka modal kembali jika ingin mengganti pelanggan -->
                                        <div class="text-end mt-2">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary" id="btnChangePelanggan" style="display: none;">
                                                <i class="fas fa-exchange-alt mr-1"></i> Ganti
                                            </a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-outline-danger ms-1" id="btnRemovePelanggan" style="display: none;">
                                                <i class="fas fa-times mr-1"></i> Hapus
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paket Layanan -->
                    <div class="card booking-card">
                        <div class="card-header">
                            <h5><i class="fas fa-list mr-2"></i> Paket Layanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div id="selectedPaketsContainer" class="mb-3">
                                            <!-- Paket yang dipilih akan ditampilkan di sini -->
                                        </div>

                                        <button type="button" class="btn btn-primary" id="tambahPaket">
                                            <i class="fas fa-plus-circle mr-1"></i> Tambah Paket
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Waktu Booking -->
                    <div class="card booking-card">
                        <div class="card-header">
                            <h5><i class="fas fa-clock mr-2"></i> Waktu Booking</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="tanggal_booking" class="form-label">Tanggal Booking</label>
                                        <div class="date-picker-container">
                                            <i class="fas fa-calendar calendar-icon"></i>
                                            <input type="date" class="form-control" id="tanggal_booking" name="tanggal_booking" min="<?= date('Y-m-d') ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pilihan Jam -->
                            <div id="timeSlotContainer" class="mt-3" style="display: none;">
                                <div class="alert alert-info booking-date-display mb-3" id="bookingDateDisplay">
                                    <i class="fas fa-info-circle mr-2"></i> Silakan pilih tanggal terlebih dahulu
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row time-slots" id="timeSlotGrid">
                                            <div class="col-4 col-md-2 mb-2">
                                                <div class="time-slot text-center" data-time="09:00">09:00</div>
                                            </div>
                                            <div class="col-4 col-md-2 mb-2">
                                                <div class="time-slot text-center" data-time="10:00">10:00</div>
                                            </div>
                                            <div class="col-4 col-md-2 mb-2">
                                                <div class="time-slot text-center" data-time="11:00">11:00</div>
                                            </div>
                                            <div class="col-4 col-md-2 mb-2">
                                                <div class="time-slot text-center" data-time="12:00">12:00</div>
                                            </div>
                                            <div class="col-4 col-md-2 mb-2">
                                                <div class="time-slot text-center" data-time="13:00">13:00</div>
                                            </div>
                                            <div class="col-4 col-md-2 mb-2">
                                                <div class="time-slot text-center" data-time="14:00">14:00</div>
                                            </div>
                                            <div class="col-4 col-md-2 mb-2">
                                                <div class="time-slot text-center" data-time="15:00">15:00</div>
                                            </div>
                                            <div class="col-4 col-md-2 mb-2">
                                                <div class="time-slot text-center" data-time="16:00">16:00</div>
                                            </div>
                                            <div class="col-4 col-md-2 mb-2">
                                                <div class="time-slot text-center" data-time="17:00">17:00</div>
                                            </div>
                                            <div class="col-4 col-md-2 mb-2">
                                                <div class="time-slot text-center" data-time="18:00">18:00</div>
                                            </div>
                                            <div class="col-4 col-md-2 mb-2">
                                                <div class="time-slot text-center" data-time="19:00">19:00</div>
                                            </div>
                                            <div class="col-4 col-md-2 mb-2">
                                                <div class="time-slot text-center" data-time="20:00">20:00</div>
                                            </div>
                                        </div>

                                        <div class="status-legend mt-3 p-2 bg-light rounded border">
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
                                                <span>Sudah Dibooking</span>
                                            </div>
                                            <div class="legend-item">
                                                <div class="legend-color legend-disabled"></div>
                                                <span>Tidak Tersedia</span>
                                            </div>
                                        </div>

                                        <input type="hidden" id="jamstart" name="jamstart" required>
                                        <input type="hidden" id="jamend" name="jamend">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Karyawan Card -->
                    <div class="card booking-card">
                        <div class="card-header">
                            <h5><i class="fas fa-user-tie mr-2"></i> Karyawan</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info" id="karyawanAlert">
                                <i class="fas fa-info-circle mr-2"></i> Silakan pilih tanggal dan jam terlebih dahulu.
                            </div>

                            <div id="karyawanContainer" style="display: none;">
                                <!-- Button untuk membuka modal -->
                                <a href="javascript:void(0);" class="btn btn-primary mb-3" id="btnSearchKaryawan">
                                    <i class="fas fa-search mr-1"></i> Pilih Karyawan
                                </a>

                                <div class="selected-karyawan">
                                    <div class="customer-card">
                                        <h6>Karyawan Terpilih</h6>
                                        <div id="selectedKaryawanInfo">
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle mr-2"></i> Belum ada karyawan yang dipilih. Silakan klik tombol "Pilih Karyawan" di atas.
                                            </div>
                                        </div>
                                        <input type="hidden" id="idkaryawan" name="idkaryawan" required>

                                        <!-- Tombol untuk membuka modal kembali jika ingin mengganti karyawan -->
                                        <div class="text-end mt-2">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary" id="btnChangeKaryawan" style="display: none;">
                                                <i class="fas fa-exchange-alt mr-1"></i> Ganti
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Booking -->
                    <div class="card booking-card">
                        <div class="card-header">
                            <h5><i class="fas fa-receipt mr-2"></i> Ringkasan Booking</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%">Total Durasi</td>
                                        <td width="5%">:</td>
                                        <td id="total_durasi">0 menit</td>
                                    </tr>
                                    <tr>
                                        <td>Total Harga</td>
                                        <td>:</td>
                                        <td id="total_harga" class="fw-bold text-primary">Rp 0</td>
                                    </tr>
                                </table>
                            </div>

                            <input type="hidden" name="total" value="0">
                            <input type="hidden" name="durasi_total" value="0">
                            <input type="hidden" name="selected_pakets" value="">

                            <!-- Pembayaran -->
                            <div class="mt-3">
                                <label for="jenispembayaran" class="form-label">Jenis Pembayaran</label>
                                <select class="form-select" id="jenispembayaran" name="jenispembayaran" required>
                                    <option value="">-- Pilih Jenis Pembayaran --</option>
                                    <option value="DP">DP (50%)</option>
                                    <option value="Lunas">Lunas</option>
                                </select>
                            </div>

                            <div class="payment-details mt-3">
                                <div class="form-group">
                                    <label for="jumlahbayar" class="form-label">Jumlah Bayar</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="jumlahbayar" name="jumlahbayar" required>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                    <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                                        <option value="cash">Cash</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="qris">QRIS</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="btnSubmit">
                                    <i class="fas fa-save mr-1"></i> Simpan Booking
                                </button>
                                <a href="<?= site_url('admin/booking') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                            </div>
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
        // Konfigurasi toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 5000,
            extendedTimeOut: 2000,
            preventDuplicates: true
        };

        // Variabel global untuk menyimpan data paket yang dipilih
        let selectedPakets = [];
        let totalHarga = 0;
        let totalDurasi = 0;
        let selectedTimeSlot = null;
        let availableKaryawan = [];

        // Event handler untuk tombol "Cari Pelanggan"
        $('#btnSearchCustomer').on('click', function() {
            const pelangganModal = new bootstrap.Modal(document.getElementById('pelangganModal'));
            loadPelangganData();
            pelangganModal.show();
        });

        // Event handler untuk tombol "Ganti Pelanggan"
        $('#btnChangePelanggan').on('click', function() {
            const pelangganModal = new bootstrap.Modal(document.getElementById('pelangganModal'));
            loadPelangganData();
            pelangganModal.show();
        });

        // Event handler untuk tombol "Hapus Pelanggan"
        $('#btnRemovePelanggan').on('click', function() {
            $('#idpelanggan').val('');
            $('#selectedCustomerInfo').html(`
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Belum ada pelanggan yang dipilih. Silakan klik tombol "Cari Pelanggan" di atas.
                </div>
            `);
            $('#btnChangePelanggan, #btnRemovePelanggan').hide();
            $('.selected-customer').hide();
            $('#btnSearchCustomer').show();
        });

        // Load data pelanggan
        function loadPelangganData() {
            $('#customerLoading').show();
            $('#customerEmpty').hide();
            $('#customerTableBody').empty();

            $.ajax({
                url: '<?= site_url('admin/booking/getAllPelanggan') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#customerLoading').hide();

                    if (response.status === 'success' && response.data && response.data.length > 0) {
                        $.each(response.data, function(index, pelanggan) {
                            $('#customerTableBody').append(`
                                <tr class="select-pelanggan" data-id="${pelanggan.id}" data-nama="${pelanggan.nama}" data-nohp="${pelanggan.nohp}" data-email="${pelanggan.email || '-'}" data-alamat="${pelanggan.alamat || '-'}">
                                    <td>${pelanggan.nama}</td>
                                    <td>${pelanggan.nohp}</td>
                                    <td>${pelanggan.email || '-'}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-pilih-pelanggan">
                                            <i class="fas fa-check"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        $('#customerEmpty').show();
                    }
                },
                error: function(xhr, status, error) {
                    $('#customerLoading').hide();
                    toastr.error('Gagal memuat data pelanggan');
                    console.error(error);
                }
            });
        }

        // Filter pelanggan
        $('#customerSearch').on('input', function() {
            const searchText = $(this).val().toLowerCase();

            $('#customerTableBody tr').each(function() {
                const nama = $(this).data('nama').toLowerCase();
                const nohp = $(this).data('nohp').toLowerCase();
                const email = $(this).data('email').toLowerCase();

                if (nama.includes(searchText) || nohp.includes(searchText) || email.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Pilih pelanggan
        $(document).on('click', '.btn-pilih-pelanggan, .select-pelanggan', function() {
            const row = $(this).hasClass('select-pelanggan') ? $(this) : $(this).closest('tr');
            const idpelanggan = row.data('id');
            const nama = row.data('nama');
            const nohp = row.data('nohp');
            const email = row.data('email');
            const alamat = row.data('alamat');

            $('#idpelanggan').val(idpelanggan);

            $('#selectedCustomerInfo').html(`
                <div class="mb-2">
                    <strong>Nama:</strong> ${nama}
                </div>
                <div class="mb-2">
                    <strong>No. HP:</strong> ${nohp}
                </div>
                <div class="mb-2">
                    <strong>Email:</strong> ${email}
                </div>
                <div>
                    <strong>Alamat:</strong> ${alamat}
                </div>
            `);

            $('#btnSearchCustomer').hide();
            $('.selected-customer').show();
            $('#btnChangePelanggan, #btnRemovePelanggan').show();

            bootstrap.Modal.getInstance(document.getElementById('pelangganModal')).hide();
        });

        // Event handler untuk tombol "Tambah Pelanggan Baru"
        $('#btnAddNewCustomer').on('click', function() {
            $('#customerTableBody').hide();
            $('#customerEmpty').hide();
            $('#newCustomerForm').show();
        });

        // Event handler untuk tombol "Batal" pada form tambah pelanggan baru
        $('#btnCancelNewCustomer').on('click', function() {
            $('#newCustomerForm').hide();
            $('#customerTableBody').show();
            $('#customerEmpty').show();
            // Reset form
            $('#new_nama, #new_nohp, #new_email, #new_alamat').val('');
        });

        // Event handler untuk tombol "Simpan" pada form tambah pelanggan baru
        $('#btnSaveNewCustomer').on('click', function() {
            const nama = $('#new_nama').val();
            const nohp = $('#new_nohp').val();
            const email = $('#new_email').val();
            const alamat = $('#new_alamat').val();

            if (!nama || !nohp || !email) {
                toastr.warning('Nama, Nomor HP, dan Email wajib diisi');
                return;
            }

            $.ajax({
                url: '<?= site_url('admin/booking/storePelanggan') ?>',
                type: 'POST',
                data: {
                    nama_lengkap: nama,
                    no_hp: nohp,
                    email: email,
                    alamat: alamat
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#btnSaveNewCustomer').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success('Pelanggan berhasil ditambahkan');

                        // Pilih pelanggan yang baru ditambahkan
                        $('#idpelanggan').val(response.data.id);

                        $('#selectedCustomerInfo').html(`
                            <div class="mb-2">
                                <strong>Nama:</strong> ${nama}
                            </div>
                            <div class="mb-2">
                                <strong>No. HP:</strong> ${nohp}
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> ${email}
                            </div>
                            <div>
                                <strong>Alamat:</strong> ${alamat || '-'}
                            </div>
                        `);

                        $('#btnSearchCustomer').hide();
                        $('.selected-customer').show();
                        $('#btnChangePelanggan, #btnRemovePelanggan').show();

                        // Tutup modal
                        bootstrap.Modal.getInstance(document.getElementById('pelangganModal')).hide();

                        // Reset form
                        $('#new_nama, #new_nohp, #new_email, #new_alamat').val('');
                        $('#newCustomerForm').hide();
                        $('#customerTableBody').show();
                    } else {
                        toastr.error(response.message || 'Gagal menambahkan pelanggan');
                    }
                    $('#btnSaveNewCustomer').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
                },
                error: function(xhr, status, error) {
                    toastr.error('Terjadi kesalahan saat menyimpan data');
                    console.error(error);
                    $('#btnSaveNewCustomer').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
                }
            });
        });

        // Event handler untuk tombol "Tambah Paket"
        $('#tambahPaket').on('click', function() {
            const paketModal = new bootstrap.Modal(document.getElementById('paketModal'));
            paketModal.show();
        });

        // Filter paket
        $('#searchPaket').on('input', function() {
            const searchText = $(this).val().toLowerCase();

            $('.paket-item').each(function() {
                const namaPaket = $(this).data('nama').toLowerCase();

                if (namaPaket.includes(searchText)) {
                    $(this).closest('.col-md-4').show();
                } else {
                    $(this).closest('.col-md-4').hide();
                }
            });
        });

        // Pilih paket
        $(document).on('click', '.select-paket, .paket-item', function(e) {
            // Hanya proses jika yang diklik adalah tombol pilih atau card paket
            if (!$(e.target).hasClass('select-paket') && !$(e.target).closest('.select-paket').length && !$(this).hasClass('paket-item')) {
                return;
            }

            // Hindari event bubbling yang menyebabkan fungsi dipanggil dua kali
            e.stopPropagation();

            const card = $(this).hasClass('paket-item') ? $(this) : $(this).closest('.paket-item');
            const idpaket = card.data('id');
            const namaPaket = card.data('nama');
            const harga = parseInt(card.data('harga'));
            const durasi = parseInt(card.data('durasi'));

            // Cek apakah paket sudah dipilih
            const existingPaket = selectedPakets.find(p => p.id === idpaket);

            if (existingPaket && selectedPakets.length > 0) {
                toastr.warning('Paket ini sudah dipilih');
                return;
            }

            // Tambahkan paket ke array
            selectedPakets.push({
                id: idpaket,
                nama: namaPaket,
                harga: harga,
                durasi: durasi
            });

            // Update UI
            updateSelectedPaketsUI();
            hitungTotal();

            // Tutup modal
            const paketModal = bootstrap.Modal.getInstance(document.getElementById('paketModal'));
            if (paketModal) {
                paketModal.hide();
            }

            // Reset filter
            $('#searchPaket').val('');
            $('.paket-item').closest('.col-md-4').show();
        });

        // Hapus paket dari daftar
        $(document).on('click', '.btn-hapus-paket', function() {
            const idpaket = $(this).data('id');

            // Hapus paket dari array
            selectedPakets = selectedPakets.filter(p => p.id !== idpaket);

            // Update UI
            updateSelectedPaketsUI();
            hitungTotal();

            // Jika paket kosong, reset jam dan karyawan
            if (selectedPakets.length === 0) {
                resetTimeAndKaryawan();
            }
        });

        // Update UI untuk paket yang dipilih
        function updateSelectedPaketsUI() {
            const container = $('#selectedPaketsContainer');

            if (selectedPakets.length === 0) {
                container.html(`
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Belum ada paket yang dipilih
                    </div>
                `);
                return;
            }

            let html = '';

            selectedPakets.forEach((paket, index) => {
                html += `
                    <div class="card mb-2 selected-paket-item">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">${paket.nama}</h6>
                                    <div class="text-muted small">
                                        <span class="badge bg-info text-white me-2">${paket.durasi} menit</span>
                                        <span class="fw-bold text-primary">Rp ${formatRupiah(paket.harga)}</span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-paket" data-id="${paket.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.html(html);
        }

        // Hitung total harga dan durasi
        function hitungTotal() {
            totalHarga = 0;
            totalDurasi = 0;

            selectedPakets.forEach(paket => {
                totalHarga += paket.harga;
                totalDurasi += paket.durasi;
            });

            $('#total_harga').text(`Rp ${formatRupiah(totalHarga)}`);
            $('#total_durasi').text(`${totalDurasi} menit`);

            $('input[name="total"]').val(totalHarga);
            $('input[name="durasi_total"]').val(totalDurasi);
            $('input[name="selected_pakets"]').val(JSON.stringify(selectedPakets));

            // Update jumlah bayar jika jenis pembayaran sudah dipilih
            const jenisPembayaran = $('#jenispembayaran').val();
            if (jenisPembayaran === 'DP') {
                $('#jumlahbayar').val(totalHarga / 2);
            } else if (jenisPembayaran === 'Lunas') {
                $('#jumlahbayar').val(totalHarga);
            }
        }

        // Format angka ke format rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Event handler untuk perubahan tanggal
        $('#tanggal_booking').on('change', function() {
            const tanggal = $(this).val();

            if (!tanggal) {
                $('#timeSlotContainer').hide();
                resetTimeAndKaryawan();
                return;
            }

            if (selectedPakets.length === 0) {
                toastr.warning('Silakan pilih paket terlebih dahulu');
                $(this).val('');
                return;
            }

            // Format tanggal untuk display
            const options = {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            const formattedDate = new Date(tanggal).toLocaleDateString('id-ID', options);

            $('#bookingDateDisplay').html(`
                <i class="fas fa-info-circle mr-2"></i> Jadwal untuk <strong>${formattedDate}</strong>
            `);

            $('#timeSlotContainer').show();

            // Reset pilihan jam
            resetTimeSlots();

            // Load jadwal yang tersedia
            loadAvailableTimeSlots(tanggal);
        });

        // Reset time slots
        function resetTimeSlots() {
            $('.time-slot').removeClass('active booked partial-booked disabled');
            selectedTimeSlot = null;
            $('#jamstart').val('');
            $('#jamend').val('');
            resetKaryawan();
        }

        // Reset karyawan
        function resetKaryawan() {
            $('#karyawanContainer').hide();
            $('#karyawanAlert').show().html(`
                <i class="fas fa-info-circle mr-2"></i> Silakan pilih tanggal dan jam terlebih dahulu.
            `).removeClass('alert-warning').addClass('alert-info');
            $('#idkaryawan').val('');
            $('#selectedKaryawanInfo').html(`
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Belum ada karyawan yang dipilih. Silakan klik tombol "Pilih Karyawan" di atas.
                </div>
            `);
            $('#btnChangeKaryawan').hide();
            availableKaryawan = [];
        }

        // Reset waktu dan karyawan
        function resetTimeAndKaryawan() {
            $('#timeSlotContainer').hide();
            resetTimeSlots();
            resetKaryawan();
        }

        // Load jadwal yang tersedia
        function loadAvailableTimeSlots(tanggal) {
            // Disable semua time slot terlebih dahulu
            $('.time-slot').addClass('disabled');

            $.ajax({
                url: '<?= site_url('admin/booking/check-available-slots') ?>',
                type: 'POST',
                data: {
                    tanggal: tanggal,
                    durasi: totalDurasi
                },
                dataType: 'json',
                success: function(response) {
                    // Reset semua slot
                    $('.time-slot').removeClass('disabled booked partial-booked');

                    if (response.slots) {
                        // Update status setiap slot
                        for (const [time, status] of Object.entries(response.slots)) {
                            const slot = $(`.time-slot[data-time="${time}"]`);

                            if (status === 'booked') {
                                slot.addClass('booked');
                            } else if (status === 'partial') {
                                slot.addClass('partial-booked');
                            } else if (status === 'disabled') {
                                slot.addClass('disabled');
                            }
                        }
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Gagal memuat jadwal yang tersedia');
                    console.error(error);
                }
            });
        }

        // Pilih time slot
        $(document).on('click', '.time-slot', function() {
            if ($(this).hasClass('disabled') || $(this).hasClass('booked')) {
                return;
            }

            $('.time-slot').removeClass('active');
            $(this).addClass('active');

            const jamMulai = $(this).data('time');

            // Hitung jam selesai berdasarkan durasi total
            const [hour, minute] = jamMulai.split(':').map(Number);
            const startMinutes = hour * 60 + minute;
            const endMinutes = startMinutes + totalDurasi;

            const endHour = Math.floor(endMinutes / 60);
            const endMinute = endMinutes % 60;

            const jamSelesai = `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}`;

            $('#jamstart').val(jamMulai);
            $('#jamend').val(jamSelesai);
            selectedTimeSlot = jamMulai;

            // Load karyawan yang tersedia
            loadAvailableKaryawan();
        });

        // Load karyawan yang tersedia
        function loadAvailableKaryawan() {
            const tanggal = $('#tanggal_booking').val();
            const jamMulai = $('#jamstart').val();
            const jamSelesai = $('#jamend').val();

            if (!tanggal || !jamMulai || !jamSelesai) {
                resetKaryawan();
                return;
            }

            $.ajax({
                url: '<?= site_url('admin/booking/check-available-karyawan') ?>',
                type: 'POST',
                data: {
                    tanggal: tanggal,
                    jamMulai: jamMulai,
                    jamSelesai: jamSelesai
                },
                dataType: 'json',
                success: function(response) {
                    if (response.karyawan && response.karyawan.length > 0) {
                        availableKaryawan = response.karyawan;
                        $('#karyawanContainer').show();
                        $('#karyawanAlert').hide();
                    } else {
                        $('#karyawanContainer').hide();
                        $('#karyawanAlert').show().html(`
                            <i class="fas fa-exclamation-triangle text-warning mr-2"></i> 
                            Tidak ada karyawan yang tersedia pada waktu yang dipilih. Silakan pilih waktu lain.
                        `).removeClass('alert-info').addClass('alert-warning');
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Gagal memuat karyawan yang tersedia');
                    console.error(error);
                }
            });
        }

        // Event handler untuk tombol "Pilih Karyawan"
        $('#btnSearchKaryawan').on('click', function() {
            const karyawanModal = new bootstrap.Modal(document.getElementById('karyawanModal'));
            loadKaryawanData();
            karyawanModal.show();
        });

        // Event handler untuk tombol "Ganti Karyawan"
        $('#btnChangeKaryawan').on('click', function() {
            const karyawanModal = new bootstrap.Modal(document.getElementById('karyawanModal'));
            loadKaryawanData();
            karyawanModal.show();
        });

        // Load data karyawan
        function loadKaryawanData() {
            $('#karyawanTableBody').empty();

            if (availableKaryawan.length > 0) {
                $.each(availableKaryawan, function(index, karyawan) {
                    $('#karyawanTableBody').append(`
                        <tr class="select-karyawan" data-id="${karyawan.idkaryawan}" data-nama="${karyawan.namakaryawan}">
                            <td>${karyawan.namakaryawan}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary btn-pilih-karyawan">
                                    <i class="fas fa-check"></i> Pilih
                                </button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                $('#karyawanTableBody').html(`
                    <tr>
                        <td colspan="2" class="text-center">Tidak ada karyawan yang tersedia</td>
                    </tr>
                `);
            }
        }

        // Pilih karyawan
        $(document).on('click', '.btn-pilih-karyawan, .select-karyawan', function() {
            const row = $(this).hasClass('select-karyawan') ? $(this) : $(this).closest('tr');
            const idkaryawan = row.data('id');
            const nama = row.data('nama');

            $('#idkaryawan').val(idkaryawan);

            $('#selectedKaryawanInfo').html(`
                <div class="mb-2">
                    <strong>Nama:</strong> ${nama}
                </div>
            `);

            $('#btnChangeKaryawan').show();

            bootstrap.Modal.getInstance(document.getElementById('karyawanModal')).hide();
        });

        // Event handler untuk jenis pembayaran
        $('#jenispembayaran').on('change', function() {
            const jenisPembayaran = $(this).val();

            if (jenisPembayaran) {
                $('.payment-details').show();

                if (jenisPembayaran === 'DP') {
                    $('#jumlahbayar').val(totalHarga / 2);
                } else if (jenisPembayaran === 'Lunas') {
                    $('#jumlahbayar').val(totalHarga);
                }
            } else {
                $('.payment-details').hide();
            }
        });

        // Form submit
        $('#bookingForm').on('submit', function(e) {
            e.preventDefault();

            // Validasi form
            if (!$('#idpelanggan').val()) {
                toastr.warning('Silakan pilih pelanggan terlebih dahulu');
                return false;
            }

            if (selectedPakets.length === 0) {
                toastr.warning('Silakan pilih minimal 1 paket layanan');
                return false;
            }

            if (!$('#tanggal_booking').val()) {
                toastr.warning('Silakan pilih tanggal booking');
                return false;
            }

            if (!$('#jamstart').val()) {
                toastr.warning('Silakan pilih jam booking');
                return false;
            }

            if (!$('#idkaryawan').val()) {
                toastr.warning('Silakan pilih karyawan');
                return false;
            }

            if (!$('#jenispembayaran').val()) {
                toastr.warning('Silakan pilih jenis pembayaran');
                return false;
            }

            // Submit form
            const formData = new FormData(this);

            $.ajax({
                url: '<?= site_url('admin/booking/store') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#btnSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.href = '<?= site_url('admin/booking') ?>';
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                        $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Booking');
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Terjadi kesalahan saat menyimpan data');
                    console.error(error);
                    $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Simpan Booking');
                }
            });
        });
    });
</script>

<!-- Modal Pelanggan -->
<div class="modal fade" id="pelangganModal" tabindex="-1" aria-labelledby="pelangganModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="pelangganModalLabel">Pilih Pelanggan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="search-input mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="customerSearch" placeholder="Cari pelanggan berdasarkan nama atau nomor HP...">
                    </div>
                </div>

                <div class="table-container">
                    <table class="table table-bordered table-sm table-hover customer-table">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="customerTableBody">
                            <!-- Data pelanggan akan diisi melalui AJAX -->
                        </tbody>
                    </table>
                    <div id="customerLoading" class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Memuat data pelanggan...</span>
                    </div>
                    <div id="customerEmpty" class="text-center py-3" style="display: none;">
                        <span class="text-muted">Tidak ada pelanggan yang ditemukan</span>
                        <div class="mt-3">
                            <button type="button" class="btn btn-success" id="btnAddNewCustomer">
                                <i class="fas fa-plus"></i> Tambah Pelanggan Baru
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Form tambah pelanggan baru -->
                <div id="newCustomerForm" style="display: none;">
                    <h5 class="mb-3">Tambah Pelanggan Baru</h5>
                    <div class="mb-3">
                        <label for="new_nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="new_nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_nohp" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="new_nohp" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="new_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="new_alamat" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" id="btnSaveNewCustomer">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="button" class="btn btn-secondary" id="btnCancelNewCustomer">
                            <i class="fas fa-times"></i> Batal
                        </button>
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
<div class="modal fade" id="karyawanModal" tabindex="-1" aria-labelledby="karyawanModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="karyawanModalLabel">Pilih Karyawan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-container">
                    <table class="table table-bordered table-sm table-hover karyawan-table">
                        <thead class="table-light">
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

<!-- Modal Paket -->
<div class="modal fade" id="paketModal" tabindex="-1" aria-labelledby="paketModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="paketModalLabel">Pilih Paket Layanan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchPaket" class="form-control" placeholder="Cari paket...">
                    </div>
                </div>
                <div class="row" id="paketList">
                    <?php foreach ($paketList as $paket): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 paket-item shadow-sm" data-id="<?= $paket['idpaket'] ?>" data-nama="<?= $paket['namapaket'] ?>" data-harga="<?= $paket['harga'] ?>" data-durasi="<?= $paket['durasi'] ?? 60 ?>">
                                <div class="card-img-top position-relative" style="height: 120px; overflow: hidden;">
                                    <?php if (!empty($paket['gambar'])): ?>
                                        <img src="<?= base_url('uploads/paket/' . $paket['gambar']) ?>" class="w-100 h-100 object-fit-cover" alt="<?= $paket['namapaket'] ?>">
                                    <?php elseif (!empty($paket['image'])): ?>
                                        <img src="<?= base_url('uploads/paket/' . $paket['image']) ?>" class="w-100 h-100 object-fit-cover" alt="<?= $paket['namapaket'] ?>">
                                    <?php else: ?>
                                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="position-absolute bottom-0 end-0 bg-primary text-white px-2 py-1">
                                        <?= $paket['durasi'] ?? 60 ?> menit
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title"><?= $paket['namapaket'] ?></h6>
                                    <p class="card-text small text-muted mb-2"><?= substr($paket['deskripsi'] ?? 'Tidak ada deskripsi', 0, 50) . (strlen($paket['deskripsi'] ?? '') > 50 ? '...' : '') ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold">Rp <?= number_format($paket['harga'], 0, ',', '.') ?></span>
                                        <button type="button" class="btn btn-sm btn-primary select-paket">
                                            <i class="fas fa-plus"></i> Pilih
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>