<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Laporan Pengeluaran</h1>
        <p class="mb-0 text-secondary">Laporan uang keluar</p>
    </div>
    <div>
        <a href="<?= site_url('admin/reports/pengeluaran/print') . '?tanggal_awal=' . $tanggalAwal . '&tanggal_akhir=' . $tanggalAkhir ?>" target="_blank" class="btn btn-primary btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
            </div>
            <div class="card-body">
                <form action="<?= site_url('admin/reports/pengeluaran') ?>" method="get">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="<?= $tanggalAwal ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="<?= $tanggalAkhir ?>">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ringkasan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Total Pengeluaran: Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?>
                        </div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Periode: <?= date('d/m/Y', strtotime($tanggalAwal)) ?> - <?= date('d/m/Y', strtotime($tanggalAkhir)) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Pengeluaran</h6>
            </div>
            <div class="card-body">
                <?php if (empty($pengeluaran)): ?>
                    <div class="alert alert-info">
                        Tidak ada data pengeluaran untuk periode <?= date('d/m/Y', strtotime($tanggalAwal)) ?> - <?= date('d/m/Y', strtotime($tanggalAkhir)) ?>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($pengeluaran as $p) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d/m/Y', strtotime($p['tgl'])) ?></td>
                                        <td><?= $p['keterangan'] ?></td>
                                        <td>Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="fw-bold">Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [
                [1, 'asc']
            ]
        });
    });
</script>
<?= $this->endSection() ?>