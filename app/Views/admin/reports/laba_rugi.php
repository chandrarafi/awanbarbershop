<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Laporan Laba Rugi</h1>
        <p class="mb-0 text-secondary">Laporan pendapatan, pengeluaran, dan laba bersih</p>
    </div>
    <div>
        <a href="<?= site_url('admin/reports/laba-rugi/print') . '?tahun=' . $tahun ?>" target="_blank" class="btn btn-primary btn-sm">
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
                <form action="<?= site_url('admin/reports/laba-rugi') ?>" method="get">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control">
                                <?php foreach ($daftarTahun as $t) : ?>
                                    <option value="<?= $t['tahun'] ?>" <?= $tahun == $t['tahun'] ? 'selected' : '' ?>><?= $t['tahun'] ?></option>
                                <?php endforeach; ?>
                            </select>
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
                <h6 class="m-0 font-weight-bold text-primary">Ringkasan Tahun <?= $tahun ?></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body py-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">
                                    Total Pendapatan
                                </div>
                                <div class="h5 mb-0 font-weight-bold">
                                    Rp <?= number_format($totalPendapatan, 0, ',', '.') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body py-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">
                                    Total Pengeluaran
                                </div>
                                <div class="h5 mb-0 font-weight-bold">
                                    Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card <?= $labaBersih >= 0 ? 'bg-primary' : 'bg-danger' ?> text-white">
                            <div class="card-body py-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">
                                    Laba Bersih
                                </div>
                                <div class="h5 mb-0 font-weight-bold">
                                    Rp <?= number_format($labaBersih, 0, ',', '.') ?>
                                </div>
                            </div>
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
                <h6 class="m-0 font-weight-bold text-primary">Laporan Laba Rugi Tahun <?= $tahun ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Bulan</th>
                                <th class="text-center">Pendapatan</th>
                                <th class="text-center">Pengeluaran</th>
                                <th class="text-center">Laba/Rugi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($dataBulanan as $bulan => $data) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $namaBulan[$bulan] ?></td>
                                    <td class="text-end">Rp <?= number_format($data['pendapatan'] ?? 0, 0, ',', '.') ?></td>
                                    <td class="text-end">Rp <?= number_format($data['pengeluaran'] ?? 0, 0, ',', '.') ?></td>
                                    <td class="<?= ($data['laba'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?> fw-bold text-end">
                                        Rp <?= number_format($data['laba'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="2" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold text-end">Rp <?= number_format($totalPendapatan ?? 0, 0, ',', '.') ?></td>
                                <td class="fw-bold text-end">Rp <?= number_format($totalPengeluaran ?? 0, 0, ',', '.') ?></td>
                                <td class="<?= ($labaBersih ?? 0) >= 0 ? 'text-success' : 'text-danger' ?> fw-bold text-end">
                                    Rp <?= number_format($labaBersih ?? 0, 0, ',', '.') ?>
                                </td>
                            </tr>
                        </tfoot>
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
        $('#dataTable').DataTable({
            "ordering": false,
            "paging": false,
            "info": false,
            "searching": false
        });
    });
</script>
<?= $this->endSection() ?>