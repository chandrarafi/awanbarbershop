<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Laporan Paket</h1>
        <p class="mb-0 text-secondary">Laporan data paket layanan lengkap</p>
    </div>
    <div>
        <a href="<?= site_url('admin/reports/paket/print') ?>" target="_blank" class="btn btn-primary btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak
        </a>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Paket</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Kode Paket</th>
                                <th>Nama Paket</th>
                                <th>Jenis Paket</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($paket as $p) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $p['idpaket'] ?></td>
                                    <td><?= $p['namapaket'] ?></td>
                                    <td><?= $p['deskripsi'] ?? '-' ?></td>
                                    <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
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
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
<?= $this->endSection() ?>