<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">Data Pengeluaran</h6>
        <button type="button" class="btn btn-primary btn-sm" id="btnAdd">
            <i class="bi bi-plus"></i> Tambah Pengeluaran
        </button>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pengeluaran</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        var table = $('#dataTable').DataTable({
            processing: true,
            ajax: {
                url: '<?= site_url('admin/pengeluaran/getPengeluaran') ?>',
                type: 'GET'
            },
            columns: [{
                    data: 0
                },
                {
                    data: 1
                },
                {
                    data: 2
                },
                {
                    data: 3
                },
                {
                    data: 4
                },
                {
                    data: 5,
                    orderable: false
                }
            ]
        });

        // Tambah Pengeluaran
        $('#btnAdd').click(function() {
            $('#formPengeluaran')[0].reset();
            $('#idpengeluaran').val('');
            $('#modalFormLabel').text('Tambah Pengeluaran');
            $('#errorAlert').hide();
            $('#modalForm').modal('show');
        });

        // Edit Pengeluaran
        $('#dataTable').on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            $('#errorAlert').hide();
            $('#modalFormLabel').text('Edit Pengeluaran');

            // Ambil data untuk edit
            $.ajax({
                url: '<?= site_url('admin/pengeluaran/edit/') ?>' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#idpengeluaran').val(response.idpengeluaran);
                    $('#tgl').val(response.tgl);
                    $('#keterangan').val(response.keterangan);
                    $('#jumlah').val(response.jumlah);
                    $('#modalForm').modal('show');
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.messages.error || 'Terjadi kesalahan saat mengambil data'
                    });
                }
            });
        });

        // Submit Form
        $('#formPengeluaran').submit(function(e) {
            e.preventDefault();

            const id = $('#idpengeluaran').val();
            const url = id ?
                '<?= site_url('admin/pengeluaran/update/') ?>' + id :
                '<?= site_url('admin/pengeluaran/store') ?>';

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    $('#modalForm').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    });
                    table.ajax.reload();
                },
                error: function(xhr, status, error) {
                    $('#errorAlert').show();
                    $('#errorList').empty();

                    if (xhr.responseJSON && xhr.responseJSON.messages) {
                        const errors = xhr.responseJSON.messages;
                        $.each(errors, function(key, value) {
                            $('#errorList').append('<li>' + value + '</li>');
                        });
                    } else {
                        $('#errorList').append('<li>Terjadi kesalahan. Silakan coba lagi.</li>');
                    }
                }
            });
        });

        // Delete Pengeluaran
        $('#dataTable').on('click', '.btn-delete', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pengeluaran akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= site_url('admin/pengeluaran/delete/') ?>' + id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            table.ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.messages.error || 'Terjadi kesalahan saat menghapus data'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">Tambah Pengeluaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPengeluaran">
                <div class="modal-body">
                    <div class="alert alert-danger" id="errorAlert" style="display: none;">
                        <ul id="errorList"></ul>
                    </div>
                    <input type="hidden" id="idpengeluaran" name="idpengeluaran">
                    <div class="mb-3">
                        <label for="tgl" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tgl" name="tgl" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>