<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Laporan Pembayaran</h1>
        <p class="mb-0 text-secondary">Laporan data pembayaran lengkap</p>
    </div>
    <div>
        <a href="<?= site_url('admin/reports/pembayaran/print') ?>" target="_blank" class="btn btn-primary btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak
        </a>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Pembayaran</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>No Transaksi</th>
                                <th>Tanggal</th>
                                <th>Nama Pelanggan</th>
                                <th>Nama Paket</th>
                                <th>Jenis Paket</th>
                                <th>Harga Paket</th>
                                <th>Total Bayar</th>
                                <th>Metode Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($pembayaran as $p) : ?>
                                <?php foreach ($p['details'] as $detail) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $p['fakturbooking'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                                        <td><?= $p['booking']['nama_lengkap'] ?></td>
                                        <td><?= $detail['nama_paket'] ?></td>
                                        <td><?= $detail['deskripsi'] ?></td>
                                        <td>Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></td>
                                        <td><?= ucfirst($p['metode']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
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