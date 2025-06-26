<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Laporan Pendapatan Tahunan</h1>
        <p class="mb-0 text-secondary">Laporan pendapatan per tahun</p>
    </div>
    <div>
        <a href="<?= site_url('admin/reports/pendapatan-tahunan/print') ?>" target="_blank" class="btn btn-primary btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak
        </a>
    </div>
</div>

<!-- Summary Card -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ringkasan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Total Pendapatan Keseluruhan: Rp <?= number_format($totalPendapatan, 0, ',', '.') ?>
                        </div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Semua Tahun
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
                <h6 class="m-0 font-weight-bold text-primary">Data Pendapatan Tahunan</h6>
            </div>
            <div class="card-body">
                <?php if (empty($pendapatan)): ?>
                    <div class="alert alert-info">
                        Tidak ada data pendapatan tahunan
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tahun</th>
                                    <th>Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($pendapatan as $p) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $p['tahun'] ?></td>
                                        <td>Rp <?= number_format($p['total'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Total:</td>
                                    <td class="fw-bold">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></td>
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
                [1, 'desc']
            ]
        });
    });
</script>
<?= $this->endSection() ?>