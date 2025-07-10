<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan</h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Laporan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Laporan Karyawan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Data Karyawan</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="<?= site_url('admin/reports/karyawan') ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Laporan Paket</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Data Paket Layanan</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-cut fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="<?= site_url('admin/reports/paket') ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Laporan Pelanggan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Data Pelanggan</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="<?= site_url('admin/reports/pelanggan') ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Laporan Booking</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Data Booking</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="<?= site_url('admin/reports/booking') ?>" class="btn btn-warning btn-sm text-white">
                                        <i class="fas fa-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Laporan Pembayaran</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Data Pembayaran</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="<?= site_url('admin/reports/pembayaran') ?>" class="btn btn-danger btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Laporan Pendapatan Bulanan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Pendapatan Per Bulan</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="<?= site_url('admin/reports/pendapatan-bulanan') ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Laporan Pendapatan Tahunan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Pendapatan Per Tahun</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="<?= site_url('admin/reports/pendapatan-tahunan') ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Laporan Pengeluaran</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Data Uang Keluar</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="<?= site_url('admin/reports/pengeluaran') ?>" class="btn btn-danger btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Laporan Uang Masuk Dan Keluar</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Uang Masuk Dan Keluar</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="<?= site_url('admin/reports/uang-masuk-keluar') ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Laporan Laba Rugi Bulanan</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Laba Rugi Per Bulan</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="<?= site_url('admin/reports/laba-rugi-bulanan') ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>