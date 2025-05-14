<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Manajemen Paket</h1>
        <p class="mb-0 text-secondary">Kelola semua paket dalam sistem</p>
    </div>
    <button type="button" class="btn btn-primary d-flex align-items-center" id="btnTambahPaket">
        <i class="bi bi-plus-circle me-2"></i> Tambah Paket
    </button>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Paket Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="paketTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Paket</th>
                                <th>Nama Paket</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded by DataTables AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles for this page -->
<style>
    /* Fix for modal backdrop */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(44, 62, 80, 0.6) !important;
        backdrop-filter: blur(4px) !important;
        -webkit-backdrop-filter: blur(4px) !important;
        z-index: 1040 !important;
    }

    .modal-backdrop.show {
        opacity: 1 !important;
    }

    .modal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 1060 !important;
        width: 100% !important;
        height: 100% !important;
        overflow-x: hidden !important;
        overflow-y: auto !important;
        outline: 0 !important;
    }

    .modal-dialog {
        margin: 1.75rem auto !important;
        max-width: 500px !important;
    }

    .modal-lg {
        max-width: 800px !important;
    }

    .modal-content {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.3) !important;
        border: none !important;
        border-radius: 0.5rem !important;
    }

    /* Responsive table styling */
    @media (max-width: 767.98px) {

        .table th,
        .table td {
            white-space: nowrap;
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        #paketTable_wrapper .row:first-child,
        #paketTable_wrapper .row:last-child {
            margin-bottom: 1rem;
        }

        #paketTable_wrapper .dataTables_info,
        #paketTable_wrapper .dataTables_paginate {
            text-align: center !important;
            float: none !important;
            margin-top: 0.5rem;
        }

        .modal-dialog {
            margin: 0.75rem auto !important;
            max-width: calc(100% - 20px) !important;
        }

        .modal-lg {
            max-width: calc(100% - 20px) !important;
        }
    }

    /* Fix for Tampilkan X data */
    .dataTables_length {
        margin-bottom: 10px;
    }

    .dataTables_length select {
        min-width: 60px;
        padding: 0.35rem;
        border-radius: 0.25rem;
        margin: 0 5px;
        border-color: #dee2e6;
    }

    .dataTables_filter {
        margin-bottom: 10px;
    }

    @media (max-width: 767.98px) {

        .dataTables_length,
        .dataTables_filter {
            text-align: left !important;
            display: block;
            width: 100%;
        }
    }

    /* Action buttons in table */
    .btn-action {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    /* Badge styles */
    .badge {
        font-weight: 600;
        padding: 0.35rem 0.65rem;
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function() {
        let paketTable = $('#paketTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/paket/getPaket') ?>',
                type: 'GET',
                dataSrc: function(json) {
                    if (json.recordsFiltered === 0 && json.search) {
                        $('.dataTables_empty').html('Tidak ditemukan data yang sesuai dengan pencarian: "' + json.search + '"');
                    }
                    return json.data;
                }
            },
            columns: [{
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'idpaket'
                },
                {
                    data: 'namapaket'
                },
                {
                    data: 'deskripsi',
                    render: function(data, type, row) {
                        if (type === 'display' && data.length > 50) {
                            return data.substr(0, 47) + '...';
                        }
                        return data;
                    }
                },
                {
                    data: 'harga',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return row.harga_formatted;
                        }
                        return data;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<div class="d-flex gap-1">' +
                            '<button class="btn btn-sm btn-info btn-edit" data-id="' + row.idpaket + '"><i class="bi bi-pencil"></i></button>' +
                            '<button class="btn btn-sm btn-danger btn-delete" data-id="' + row.idpaket + '"><i class="bi bi-trash"></i></button>' +
                            '</div>';
                    }
                }
            ],
            order: [
                [1, 'asc']
            ],
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            language: {
                processing: '<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: "Tidak ada data paket",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang sesuai",
                infoFiltered: "(disaring dari _MAX_ total data)",
                search: "Cari:",
                searchPlaceholder: "Ketik untuk mencari...",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ditemukan data yang sesuai",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            responsive: true,
            searchDelay: 100,
            deferRender: true,
            scroller: true,
            scrollY: 400,
            scrollCollapse: true
        });

        let searchTimeout;
        $('.dataTables_filter input')
            .off()
            .on('input', function() {
                clearTimeout(searchTimeout);
                let value = this.value;

                searchTimeout = setTimeout(() => {
                    paketTable.search(value).draw();
                }, 100);
            });

        $('.dataTables_processing').addClass('position-absolute start-50 translate-middle-x mt-2');

        // Reset form when modal is closed
        $('#modalPaket').on('hidden.bs.modal', function() {
            resetForm();
        });

        // Fix modal backdrop issue
        $('.modal').on('shown.bs.modal', function() {
            $('body').addClass('modal-open');
            if ($('.modal-backdrop').length === 0) {
                $('body').append('<div class="modal-backdrop show"></div>');
            }
        });

        $('.modal').on('hidden.bs.modal', function() {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        // Ensure modals are properly handled on page load
        $(window).on('load', function() {
            // Check if any modals are open and make sure backdrop is fixed
            if ($('.modal.show').length > 0) {
                $('body').addClass('modal-open');
                if ($('.modal-backdrop').length === 0) {
                    $('body').append('<div class="modal-backdrop show"></div>');
                }
            }
        });

        // Reset form errors
        function resetForm() {
            $('#formPaket')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();
            $('#generalError').hide().find('ul').empty();
        }

        // Display validation errors
        function showErrors(errors) {
            if (typeof errors === 'object' && errors !== null) {
                let hasFieldErrors = false;
                let generalErrors = [];

                $.each(errors, function(field, message) {
                    const $field = $('#' + field);
                    if ($field.length) {
                        $field.addClass('is-invalid');
                        $('#' + field + 'Error').text(message);
                        hasFieldErrors = true;
                    } else {
                        generalErrors.push(message);
                    }
                });

                if (generalErrors.length > 0) {
                    const $generalError = $('#generalError');
                    const $errorList = $generalError.find('ul');
                    generalErrors.forEach(function(error) {
                        $errorList.append('<li>' + error + '</li>');
                    });
                    $generalError.show();
                }
            } else if (typeof errors === 'string') {
                $('#generalError').show().find('ul').append('<li>' + errors + '</li>');
            }
        }

        // Tambah Paket
        $('#btnTambahPaket').on('click', function() {
            resetForm();
            $('#modalPaketLabel').text('Tambah Paket');
            $('#formMode').val('add');

            // Get new ID
            $.ajax({
                url: '<?= base_url('admin/paket/getNewId') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#idpaket').val(response.idpaket);
                    $('#modalPaket').modal('show');
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal mendapatkan ID baru',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Edit Paket
        $(document).on('click', '.edit-btn', function() {
            resetForm();
            var id = $(this).data('id');

            $('#modalPaketLabel').text('Edit Paket');
            $('#formMode').val('edit');

            $.ajax({
                url: `<?= base_url('admin/paket/getById') ?>/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var data = response.data;
                        $('#idpaket').val(data.idpaket);
                        $('#namapaket').val(data.namapaket);
                        $('#deskripsi').val(data.deskripsi);
                        $('#harga').val(data.harga);
                        $('#modalPaket').modal('show');
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal mengambil data paket',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Form Submit (Add & Edit)
        $('#btnSimpan').on('click', function() {
            // Disable button and show loading state
            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

            var formData = $('#formPaket').serialize();
            var mode = $('#formMode').val();
            var id = $('#idpaket').val();
            var url = mode === 'add' ?
                '<?= base_url('admin/paket/store') ?>' :
                `<?= base_url('admin/paket/update') ?>/${id}`;

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modalPaket').modal('hide');
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        paketTable.ajax.reload();
                    } else {
                        showErrors(response.errors);
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    if (response && response.errors) {
                        showErrors(response.errors);
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menyimpan data',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                complete: function() {
                    // Re-enable button and restore label
                    $('#btnSimpan').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan');
                }
            });
        });

        // Delete Paket - Show confirmation modal
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            $('#deletePaketId').val(id);
            $('#deleteModal').modal('show');
        });

        // Confirm Delete Paket
        $('#btnConfirmDelete').on('click', function() {
            // Disable button and show loading state
            $(this).attr('disabled', true);
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...');

            var id = $('#deletePaketId').val();

            $.ajax({
                url: `<?= base_url('admin/paket/delete') ?>/${id}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#deleteModal').modal('hide');
                        Swal.fire({
                            title: 'Sukses',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        paketTable.ajax.reload();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan pada server',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    // Re-enable button and restore label
                    $('#btnConfirmDelete').attr('disabled', false);
                    $('#btnConfirmDelete').html('<i class="bi bi-trash me-1"></i> Hapus');
                }
            });
        });

        // Improve DataTable responsiveness
        $(window).on('resize', function() {
            if ($(window).width() < 768) {
                $('.dataTables_length select').addClass('form-select-sm');
            } else {
                $('.dataTables_length select').removeClass('form-select-sm');
            }
        }).trigger('resize');
    });
</script>

<!-- Modal Tambah/Edit Paket -->
<div class="modal fade" id="modalPaket" tabindex="-1" aria-labelledby="modalPaketLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="modalPaketLabel">Tambah Paket</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPaket">
                    <!-- Alert untuk error umum -->
                    <div id="generalError" class="alert alert-danger" style="display: none;">
                        <ul class="mb-0"></ul>
                    </div>

                    <input type="hidden" id="formMode" value="add">

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="idpaket" class="form-label">ID Paket</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-upc"></i></span>
                                <input type="text" class="form-control" id="idpaket" name="idpaket" readonly>
                                <div class="invalid-feedback" id="idpaketError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="namapaket" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-box"></i></span>
                                <input type="text" class="form-control" id="namapaket" name="namapaket" required>
                                <div class="invalid-feedback" id="namapaketError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                                <div class="invalid-feedback" id="deskripsiError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                            <div class="input-group has-validation">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="harga" name="harga" required>
                                <div class="invalid-feedback" id="hargaError"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpan">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center fs-5">Apakah Anda yakin ingin menghapus paket ini?</p>
                <p class="text-center text-secondary">Tindakan ini tidak dapat dibatalkan dan semua data terkait akan dihapus secara permanen.</p>
                <input type="hidden" id="deletePaketId">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-danger" id="btnConfirmDelete">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>