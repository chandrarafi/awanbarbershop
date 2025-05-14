<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Manajemen Pelanggan</h1>
        <p class="mb-0 text-secondary">Kelola data pelanggan yang terdaftar dalam sistem</p>
    </div>
    <a href="<?= base_url('admin/pelanggan/create') ?>" class="btn btn-primary">
        <i class="bi bi-person-plus me-2"></i> Tambah Pelanggan
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Alert untuk success/error message -->
                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Pelanggan Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="pelangganTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Pelanggan</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Jenis Kelamin</th>
                                <th>No HP</th>
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


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function() {
        var table = $('#pelangganTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "<?= base_url('admin/pelanggan/getPelanggan') ?>",
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'idpelanggan'
                },
                {
                    data: 'nama_lengkap'
                },
                {
                    data: 'username',
                    render: function(data) {
                        return data ? data : '<span class="text-muted">Tidak ada akun</span>';
                    }
                },
                {
                    data: 'email',
                    render: function(data) {
                        return data ? data : '<span class="text-muted">Tidak ada akun</span>';
                    }
                },
                {
                    data: 'jeniskelamin',
                    render: function(data) {
                        return data ? data : '<span class="text-muted">Belum diisi</span>';
                    }
                },
                {
                    data: 'no_hp',
                    render: function(data) {
                        return data ? data : '<span class="text-muted">Belum diisi</span>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<div class="d-flex gap-1">' +
                            '<a href="<?= base_url('admin/pelanggan/edit') ?>/' + row.id + '" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i></a>' +
                            '<button class="btn btn-sm btn-danger delete-btn" data-id="' + row.id + '"><i class="bi bi-trash"></i></button>' +
                            '</div>';
                    }
                }
            ],
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada data yang ditemukan",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        // Delete Pelanggan - Show confirmation modal
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            $('#deletePelangganId').val(id);
            $('#deleteModal').modal('show');
        });

        // Confirm Delete Pelanggan
        $('#btnConfirmDelete').on('click', function() {
            // Disable button and show loading state
            $(this).attr('disabled', true);
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...');

            var id = $('#deletePelangganId').val();

            $.ajax({
                url: `<?= base_url('admin/pelanggan/delete') ?>/${id}`,
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
                        table.ajax.reload();
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
    });
</script>
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
                <p class="text-center fs-5">Apakah Anda yakin ingin menghapus pelanggan ini?</p>
                <p class="text-center text-secondary">Tindakan ini tidak dapat dibatalkan dan semua data terkait akan dihapus secara permanen.</p>
                <input type="hidden" id="deletePelangganId">
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