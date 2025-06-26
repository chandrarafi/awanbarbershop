<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Laporan Laba Rugi Bulanan</h1>
        <p class="mb-0 text-secondary">Laporan pendapatan, pengeluaran, dan laba bersih per hari</p>
    </div>
    <div>
        <a href="<?= site_url('admin/reports/laba-rugi-bulanan/print') . '?tahun=' . $tahun . '&bulan=' . $bulan ?>" target="_blank" class="btn btn-primary btn-sm">
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
                <form action="<?= site_url('admin/reports/laba-rugi-bulanan') ?>" method="get">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control">
                                <?php if (empty($daftarTahun)): ?>
                                    <option value="<?= date('Y') ?>"><?= date('Y') ?></option>
                                <?php else: ?>
                                    <?php foreach ($daftarTahun as $t): ?>
                                        <option value="<?= $t['tahun'] ?>" <?= ($tahun == $t['tahun']) ? 'selected' : '' ?>><?= $t['tahun'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select name="bulan" id="bulan" class="form-control">
                                <option value="01" <?= ($bulan == '01') ? 'selected' : '' ?>>Januari</option>
                                <option value="02" <?= ($bulan == '02') ? 'selected' : '' ?>>Februari</option>
                                <option value="03" <?= ($bulan == '03') ? 'selected' : '' ?>>Maret</option>
                                <option value="04" <?= ($bulan == '04') ? 'selected' : '' ?>>April</option>
                                <option value="05" <?= ($bulan == '05') ? 'selected' : '' ?>>Mei</option>
                                <option value="06" <?= ($bulan == '06') ? 'selected' : '' ?>>Juni</option>
                                <option value="07" <?= ($bulan == '07') ? 'selected' : '' ?>>Juli</option>
                                <option value="08" <?= ($bulan == '08') ? 'selected' : '' ?>>Agustus</option>
                                <option value="09" <?= ($bulan == '09') ? 'selected' : '' ?>>September</option>
                                <option value="10" <?= ($bulan == '10') ? 'selected' : '' ?>>Oktober</option>
                                <option value="11" <?= ($bulan == '11') ? 'selected' : '' ?>>November</option>
                                <option value="12" <?= ($bulan == '12') ? 'selected' : '' ?>>Desember</option>
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
                <h6 class="m-0 font-weight-bold text-primary">Ringkasan</h6>
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
                                    Rp <?= number_format($totalPendapatan ?? 0, 0, ',', '.') ?>
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
                                    Rp <?= number_format($totalPengeluaran ?? 0, 0, ',', '.') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card <?= ($labaBersih ?? 0) >= 0 ? 'bg-primary' : 'bg-danger' ?> text-white">
                            <div class="card-body py-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">
                                    Laba Bersih
                                </div>
                                <div class="h5 mb-0 font-weight-bold">
                                    Rp <?= number_format($labaBersih ?? 0, 0, ',', '.') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1 mt-3">
                    <?php
                    $namaBulanList = [
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember'
                    ];
                    echo $namaBulanList[$bulan] . ' ' . $tahun;
                    ?>
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
                <h6 class="m-0 font-weight-bold text-primary">Laporan Laba Rugi Bulan <?= $namaBulanList[$bulan] ?> <?= $tahun ?></h6>
            </div>
            <div class="card-body">
                <?php if (empty($dataHarian)): ?>
                    <div class="alert alert-info">
                        Tidak ada data laba rugi untuk bulan <?= $namaBulanList[$bulan] ?> <?= $tahun ?>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Pendapatan</th>
                                    <th class="text-center">Pengeluaran</th>
                                    <th class="text-center">Laba/Rugi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($dataHarian as $tanggal => $data) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d/m/Y', strtotime($tanggal)) ?></td>
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
            "ordering": false,
            "paging": false,
            "info": false,
            "searching": false
        });
    });
</script>
<?= $this->endSection() ?>