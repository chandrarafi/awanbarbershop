<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        border: none;
        position: relative;
        z-index: 1;
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #f0f0f0;
        padding: 18px 20px;
    }

    .card-title {
        font-weight: 600;
        color: #333;
        font-size: 20px;
        margin-bottom: 0;
    }

    .card-body {
        padding: 20px;
    }

    .btn-add {
        background-color: #A27B5C;
        border-color: #A27B5C;
        border-radius: 6px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.3s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-add:hover {
        background-color: #8b6b4f;
        border-color: #8b6b4f;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .status-filters {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 20px;
    }

    .filter-status {
        border-radius: 20px;
        padding: 8px 15px;
        font-weight: 500;
        transition: all 0.3s;
        margin-right: 5px;
        border: none;
    }

    .filter-status.active {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .filter-status i {
        margin-right: 5px;
    }

    .filter-status.all {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #ddd;
    }

    .filter-status.all:hover,
    .filter-status.all.active {
        background-color: #e9ecef;
    }

    .filter-status.today {
        background-color: #20c997;
        color: #fff;
    }

    .filter-status.today:hover,
    .filter-status.today.active {
        background-color: #16a085;
    }

    .filter-status.pending {
        background-color: #ffc107;
        color: #212529;
    }

    .filter-status.pending:hover,
    .filter-status.pending.active {
        background-color: #e0a800;
    }

    .filter-status.confirmed {
        background-color: #17a2b8;
        color: #fff;
    }

    .filter-status.confirmed:hover,
    .filter-status.confirmed.active {
        background-color: #138496;
    }

    .filter-status.completed {
        background-color: #28a745;
        color: #fff;
    }

    .filter-status.completed:hover,
    .filter-status.completed.active {
        background-color: #218838;
    }

    .filter-status.cancelled {
        background-color: #dc3545;
        color: #fff;
    }

    .filter-status.cancelled:hover,
    .filter-status.cancelled.active {
        background-color: #c82333;
    }

    .filter-status.no-show {
        background-color: #6c757d;
        color: #fff;
    }

    .filter-status.no-show:hover,
    .filter-status.no-show.active {
        background-color: #5a6268;
    }

    .filter-status.rejected {
        background-color: #dc3545;
        color: #fff;
    }

    .filter-status.rejected:hover,
    .filter-status.rejected.active {
        background-color: #bd2130;
    }

    .filter-status.expired {
        background-color: #6c757d;
        color: #fff;
    }

    .filter-status.expired:hover,
    .filter-status.expired.active {
        background-color: #5a6268;
    }

    .badge {
        font-size: 11px;
        padding: 5px 10px;
        border-radius: 15px;
        font-weight: 500;
    }

    .badge-pending {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-confirmed {
        background-color: #17a2b8;
        color: #fff;
    }

    .badge-completed {
        background-color: #28a745;
        color: #fff;
    }

    .badge-cancelled {
        background-color: #dc3545;
        color: #fff;
    }

    .badge-no-show {
        background-color: #6c757d;
        color: #fff;
    }

    .badge-rejected {
        background-color: #dc3545;
        color: #fff;
    }

    .badge-expired {
        background-color: #6c757d;
        color: #fff;
    }

    .action-buttons {
        white-space: nowrap;
        display: flex;
        justify-content: center;
        gap: 5px;
    }

    .btn-action {
        padding: 6px;
        margin: 0;
        border-radius: 5px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .btn-view {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: #fff;
    }

    .btn-view:hover {
        background-color: #138496;
        border-color: #138496;
        transform: translateY(-2px);
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
        color: #fff;
    }

    .btn-edit {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }

    .btn-edit:hover {
        background-color: #e0a800;
        border-color: #e0a800;
        transform: translateY(-2px);
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
        color: #212529;
    }

    .btn-status {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }

    .btn-status:hover {
        background-color: #e0a800;
        border-color: #e0a800;
        color: #212529;
        transform: translateY(-2px);
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-print {
        background-color: #28a745;
        border-color: #28a745;
        color: #fff;
    }

    .btn-print:hover {
        background-color: #218838;
        border-color: #218838;
        transform: translateY(-2px);
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
        color: #fff;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        border-color: #dee2e6;
        font-weight: 600;
    }

    .customer-info {
        display: flex;
        flex-direction: column;
    }

    .customer-name {
        font-weight: 500;
        color: #333;
    }

    .customer-phone {
        font-size: 12px;
        color: #6c757d;
    }

    .modal-content {
        border-radius: 10px;
        border: none;
    }

    .modal-header {
        background-color: #f8f9fa;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .modal-title {
        font-weight: 600;
        color: #333;
    }

    .modal-footer {
        border-top: 1px solid #f0f0f0;
    }

    .btn-modal-cancel {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff;
    }

    .btn-modal-save {
        background-color: #A27B5C;
        border-color: #A27B5C;
    }

    .btn-modal-save:hover {
        background-color: #8b6b4f;
        border-color: #8b6b4f;
    }

    @media (max-width: 767.98px) {
        .card-header.d-flex.justify-content-between {
            flex-direction: row !important;
            flex-wrap: wrap;
        }

        .card-title {
            margin-bottom: 0;
            flex: 1;
        }

        .btn-add {
            margin-left: auto;
        }

        /* Responsive sizing for filter flags (chips) */
        .status-filters {
            padding: 8px;
        }

        .status-filters .d-flex {
            gap: 6px;
        }

        .filter-status {
            padding: 7px 12px;
            font-size: 13px;
            border-radius: 9999px;
            margin-right: 0;
            /* gap handles spacing */
        }

        .filter-status i {
            font-size: .95rem;
            margin-right: 6px;
        }
    }

    @media (max-width: 575.98px) {
        .card-title {
            font-size: 18px;
        }

        .btn-add {
            padding: 6px 12px;
            font-size: 14px;
        }

        .status-filters {
            padding: 8px;
        }

        .status-filters .d-flex {
            gap: 6px;
        }

        .filter-status {
            padding: 6px 10px;
            font-size: 12px;
            border-radius: 9999px;
        }

        .filter-status i {
            font-size: .9rem;
            margin-right: 4px;
        }

        .badge {
            font-size: 10px;
            padding: 4px 8px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        Kelola Booking
                    </h3>
                    <a href="<?= site_url('admin/booking/create') ?>" class="btn btn-primary btn-add">
                        <i class="bi bi-plus-circle mr-1"></i> Tambah Booking
                    </a>
                </div>
                <div class="card-body">
                    <div class="status-filters">
                        <div class="d-flex flex-wrap">
                            <button type="button" class="btn filter-status all active" data-status="">
                                <i class="bi bi-list"></i> Semua
                            </button>
                            <button type="button" class="btn filter-status today" data-status="today">
                                <i class="bi bi-calendar-day"></i> Hari Ini
                            </button>
                            <button type="button" class="btn filter-status pending" data-status="pending">
                                <i class="bi bi-clock"></i> Menunggu
                            </button>
                            <button type="button" class="btn filter-status confirmed" data-status="confirmed">
                                <i class="bi bi-check-circle"></i> Terkonfirmasi
                            </button>
                            <button type="button" class="btn filter-status completed" data-status="completed">
                                <i class="bi bi-check-double"></i> Selesai
                            </button>
                            <button type="button" class="btn filter-status cancelled" data-status="cancelled">
                                <i class="bi bi-x-circle"></i> Dibatalkan
                            </button>
                            <button type="button" class="btn filter-status no-show" data-status="no-show">
                                <i class="bi bi-person-slash"></i> Tidak Hadir
                            </button>
                            <button type="button" class="btn filter-status rejected" data-status="rejected">
                                <i class="bi bi-x-octagon"></i> Ditolak
                            </button>
                            <button type="button" class="btn filter-status expired" data-status="expired">
                                <i class="bi bi-hourglass-bottom"></i> Kedaluwarsa
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="bookingTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Kode Booking</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Karyawan</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan diisi melalui AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script>
    $(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Inisialisasi modal
        const statusModalEl = document.getElementById('statusModal');
        let statusModal;

        if (statusModalEl) {
            statusModal = new bootstrap.Modal(statusModalEl);
        }

        let currentStatus = '';
        let dateFilter = '';

        const bookingTable = $('#bookingTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '<?= site_url('admin/booking/getBookings') ?>',
                type: 'GET',
                data: function(d) {
                    d.status = currentStatus;
                    d.date_filter = dateFilter;
                    // Tambahkan parameter untuk menggunakan grup berdasarkan kode booking
                    d.group_by_kdbooking = true;
                    return d;
                }
            },
            columns: [{
                    data: 'kdbooking',
                    render: function(data, type, row) {
                        return '<span class="font-weight-medium">' + data + '</span>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<div class="customer-info">' +
                            '<span class="customer-name">' + row.nama_lengkap + '</span>' +
                            '<span class="customer-phone"><i class="bi bi-telephone mr-1"></i>' + row.no_hp + '</span>' +
                            '</div>';
                    }
                },
                {
                    data: 'tanggal_booking',
                    render: function(data, type, row) {
                        let jamDetail = '';

                        if (row.detail_jam) {
                            // Jika ada beberapa waktu booking (dari GROUP_CONCAT)
                            if (row.detail_jam.includes(',')) {
                                jamDetail = `<div class="mt-1">
                                    <span class="badge bg-secondary text-white">
                                        <i class="bi bi-clock"></i> ${row.jumlah_paket} sesi
                                    </span>
                                </div>`;
                            } else {
                                jamDetail = `<div class="mt-1">
                                    <span class="badge bg-secondary text-white">
                                        <i class="bi bi-clock"></i> ${row.detail_jam}
                                    </span>
                                </div>`;
                            }
                        }

                        return '<i class="bi bi-calendar-date mr-1"></i> ' + data + jamDetail;
                    }
                },
                {
                    data: 'namakaryawan',
                    render: function(data, type, row) {
                        return '<i class="bi bi-person mr-1"></i> ' + (data || 'Belum ditentukan');
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        let badgeClass = '';
                        let icon = '';

                        switch (row.status) {
                            case 'pending':
                                badgeClass = 'badge-pending';
                                icon = 'bi bi-clock';
                                break;
                            case 'confirmed':
                                badgeClass = 'badge-confirmed';
                                icon = 'bi bi-check-circle';
                                break;
                            case 'completed':
                                badgeClass = 'badge-completed';
                                icon = 'bi bi-check-double';
                                break;
                            case 'cancelled':
                                badgeClass = 'badge-cancelled';
                                icon = 'bi bi-x-circle';
                                break;
                            case 'no-show':
                                badgeClass = 'badge-no-show';
                                icon = 'bi bi-person-slash';
                                break;
                            case 'rejected':
                                badgeClass = 'badge-rejected';
                                icon = 'bi bi-x-octagon';
                                break;
                            case 'expired':
                                badgeClass = 'badge-expired';
                                icon = 'bi bi-hourglass-bottom';
                                break;
                            default:
                                badgeClass = 'badge-secondary';
                                icon = 'bi bi-question-circle';
                        }

                        return '<span class="badge ' + badgeClass + '"><i class="' + icon + ' mr-1"></i>' + row.status_text + '</span>';
                    }
                },
                {
                    data: 'total_formatted',
                    render: function(data, type, row) {
                        // Tambahkan indikator jumlah paket jika ada lebih dari 1
                        let paketInfo = '';
                        if (row.jumlah_paket && row.jumlah_paket > 1) {
                            paketInfo = '<div class="mt-1"><small class="badge bg-success text-white">' + row.jumlah_paket + ' paket</small></div>';
                        }
                        return '<span class="font-weight-medium">' + data + '</span>' + paketInfo;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
                            <div class="action-buttons">
                                <a href="<?= site_url('admin/booking/show/') ?>${row.kdbooking}" class="btn btn-sm btn-action btn-view" data-bs-toggle="tooltip" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= site_url('admin/booking/edit/') ?>${row.kdbooking}" class="btn btn-sm btn-action btn-edit" data-bs-toggle="tooltip" title="Edit Booking">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-action btn-status btn-change-status" data-bs-toggle="tooltip" data-kdbooking="${row.kdbooking}" data-status="${row.status}" title="Ubah Status">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-action btn-print print-invoice" data-bs-toggle="tooltip" title="Cetak Faktur" data-kdbooking="${row.kdbooking}">
                                    <i class="bi bi-printer"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            order: [
                [2, 'desc']
            ], // Sort by tanggal_booking desc
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
            }
        });

        // Filter berdasarkan status
        $('.filter-status').on('click', function() {
            $('.filter-status').removeClass('active');
            $(this).addClass('active');

            const statusFilter = $(this).data('status');

            // Reset dateFilter jika filter bukan "today"
            if (statusFilter === 'today') {
                dateFilter = 'today';
                currentStatus = '';
            } else {
                dateFilter = '';
                currentStatus = statusFilter;
            }

            bookingTable.ajax.reload();
        });

        // Ubah status booking
        $(document).on('click', '.btn-change-status', function() {
            const kdbooking = $(this).data('kdbooking');
            const currentStatus = $(this).data('status');

            $('#kdbooking').val(kdbooking);
            $('#status').val(currentStatus);
            statusModal.show();
        });

        // Form ubah status
        // Initialize tooltips after table redraws
        bookingTable.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // Mengelola status change pada form
        $('#status').on('change', function() {
            const selectedStatus = $(this).val();
            if (selectedStatus === 'completed') {
                const kdbooking = $('#kdbooking').val();
                // Ambil informasi pembayaran booking
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
                            $('#booking_id_pembayaran').val(kdbooking);
                            $('#sisa_pembayaran').val(data.sisa);

                            // Format currency
                            $('#totalBooking').text('Rp ' + formatRupiah(data.total));
                            $('#sudahDibayar').text('Rp ' + formatRupiah(data.jumlahbayar));
                            $('#sisaPembayaran').text('Rp ' + formatRupiah(data.sisa));

                            // Tampilkan form pelunasan jika belum lunas
                            if (parseFloat(data.sisa) > 0) {
                                $('#sisaPembayaranInfo').show();
                                $('#formPelunasan').show();
                            } else {
                                $('#sisaPembayaranInfo').show();
                                $('#formPelunasan').hide();
                            }
                        } else {
                            console.error("Error fetching payment info:", response.message);
                            $('#sisaPembayaranInfo').hide();
                        }
                    },
                    error: function() {
                        console.error("AJAX Error fetching payment info");
                        $('#sisaPembayaranInfo').hide();
                    }
                });
            } else {
                $('#sisaPembayaranInfo').hide();
            }
        });

        // Helper function untuk format angka ke format Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        $('#statusForm').on('submit', function(e) {
            e.preventDefault();

            const kdbooking = $('#kdbooking').val();
            const status = $('#status').val();

            // Data pembayaran pelunasan jika dipilih
            let dataToSend = {
                kdbooking: kdbooking,
                status: status
            };

            // Tambahkan data pelunasan jika status completed dan pelunasan dicentang
            if (status === 'completed' && $('#formPelunasan').is(':visible') && $('#lunaskan').is(':checked')) {
                dataToSend.pelunasan = true;
                dataToSend.metode_pembayaran = $('#metode_pembayaran').val();
                dataToSend.jumlah_pembayaran = $('#sisa_pembayaran').val();
            }

            $.ajax({
                url: '<?= site_url('admin/booking/updateStatus') ?>',
                type: 'POST',
                data: dataToSend,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        statusModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        bookingTable.ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
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

        // Fungsi untuk mencetak faktur langsung
        $(document).on('click', '.print-invoice', function() {
            const kdbooking = $(this).data('kdbooking');

            Swal.fire({
                title: 'Mencetak Faktur',
                text: 'Sedang mempersiapkan faktur...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Panggil fungsi cetak faktur
            cetakFaktur(kdbooking);
        });

        // Fungsi untuk mencetak faktur ke halaman baru
        function cetakFaktur(kdbooking) {
            try {
                // Buka window cetak baru
                let printWindow = window.open('', '_blank', 'height=600,width=800');

                if (!printWindow) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Popup blocker mungkin menghalangi jendela cetak. Mohon izinkan popup untuk situs ini.'
                    });
                    return;
                }

                // Ambil konten faktur dari server
                $.ajax({
                    url: '<?= site_url('admin/booking/print-invoice') ?>',
                    type: 'POST',
                    data: {
                        kdbooking: kdbooking
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Tulis konten faktur ke window cetak
                            printWindow.document.write('<!DOCTYPE html>');
                            printWindow.document.write('<html lang="id">');
                            printWindow.document.write('<head>');
                            printWindow.document.write('<meta charset="UTF-8">');
                            printWindow.document.write('<meta name="viewport" content="width=device-width, initial-scale=1.0">');
                            printWindow.document.write('<title>Faktur Booking - ' + kdbooking + '</title>');
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
                            printWindow.document.write('@media print { body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; } .no-print { display: none !important; } @page { size: A4 portrait; margin: 5mm; } }');
                            printWindow.document.write('</style>');
                            printWindow.document.write('</head>');
                            printWindow.document.write('<body>');
                            printWindow.document.write('<div class="container">');
                            printWindow.document.write(response.invoiceHtml);
                            printWindow.document.write('</div>');
                            printWindow.document.write('<script>');
                            printWindow.document.write('window.onload = function() {');
                            printWindow.document.write('  setTimeout(function() { ');
                            printWindow.document.write('    window.print();');
                            printWindow.document.write('    window.addEventListener("afterprint", function() {');
                            printWindow.document.write('      window.close();');
                            printWindow.document.write('    });');
                            printWindow.document.write('  }, 500);');
                            printWindow.document.write('};');
                            printWindow.document.write('<\/script>');
                            printWindow.document.write('</body>');
                            printWindow.document.write('</html>');

                            printWindow.document.close();
                            printWindow.focus();

                            Swal.close();
                        } else {
                            printWindow.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'Gagal memuat faktur'
                            });
                        }
                    },
                    error: function() {
                        printWindow.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat memuat faktur'
                        });
                    }
                });
            } catch (error) {
                console.error('Error cetak faktur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat mencetak faktur: ' + error.message
                });
            }
        }
    });
</script>
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
                                        <td class="text-right" id="totalBooking">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td>Sudah Dibayar</td>
                                        <td>:</td>
                                        <td class="text-right" id="sudahDibayar">Rp 0</td>
                                    </tr>
                                    <tr class="font-weight-bold">
                                        <td>Sisa</td>
                                        <td>:</td>
                                        <td class="text-right" id="sisaPembayaran">Rp 0</td>
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
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-modal-save">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>