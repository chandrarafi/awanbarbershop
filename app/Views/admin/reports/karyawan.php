<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Laporan Karyawan</h1>
        <p class="mb-0 text-secondary">Laporan data karyawan lengkap</p>
    </div>
    <div>
        <a href="<?= site_url('admin/reports/karyawan/print') ?>" target="_blank" class="btn btn-primary btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak
        </a>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="karyawanTable">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Kode Karyawan</th>
                                <th>Nama Karyawan</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>No HP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($karyawan as $k) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= $k['idkaryawan'] ?></td>
                                    <td><?= $k['namakaryawan'] ?></td>
                                    <td>
                                        <?php if ($k['jenkel'] == 'L') : ?>
                                            <span class="badge bg-primary">Laki-laki</span>
                                        <?php elseif ($k['jenkel'] == 'P') : ?>
                                            <span class="badge bg-info">Perempuan</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $k['alamat'] ?? '-' ?></td>
                                    <td><?= $k['nohp'] ?? '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
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
        $('#karyawanTable').DataTable({
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
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
    });
</script>
<?= $this->endSection() ?>