<?php if (session('role') == 'admin'): ?>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="Awan Barbershop" onerror="this.src='https://ui-avatars.com/api/?name=Awan+Barbershop&background=A27B5C&color=fff'">
            <div>
                <h3>AWAN</h3>
                <p>Barbershop</p>
            </div>
        </div>
        <hr class="sidebar-divider">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $title == 'Dashboard' ? 'active' : '' ?>" href="<?= site_url('admin') ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <div class="sidebar-heading text-uppercase fw-bold text-muted px-4 mt-4 mb-2">
                <span>Data Master</span>
            </div>
            <li class="nav-item">
                <a class="nav-link <?= $title == 'User Management' ? 'active' : '' ?>" href="<?= site_url('admin/users') ?>">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $title == 'Manajemen Karyawan' ? 'active' : '' ?>" href="<?= site_url('admin/karyawan') ?>">
                    <i class="bi bi-person-badge"></i>
                    <span>Karyawan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($title == 'Manajemen Paket' || $title == 'Tambah Paket' || $title == 'Edit Paket') ? 'active' : '' ?>" href="<?= site_url('admin/paket') ?>">
                    <i class="bi bi-scissors"></i>
                    <span>Paket</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($title == 'Manajemen Pelanggan' || $title == 'Tambah Pelanggan' || $title == 'Edit Pelanggan') ? 'active' : '' ?>" href="<?= site_url('admin/pelanggan') ?>">
                    <i class="bi bi-person-badge"></i>
                    <span>Pelanggan</span>
                </a>
            </li>
            <div class="sidebar-heading text-uppercase fw-bold text-muted px-4 mt-4 mb-2">
                <span>Transaksi</span>
            </div>
            <li class="nav-item">
                <a class="nav-link <?= ($title == 'Kelola Booking' || $title == 'Tambah Booking Baru' || $title == 'Detail Booking') ? 'active' : '' ?>" href="<?= site_url('admin/booking') ?>">
                    <i class="bi bi-calendar-check"></i>
                    <span>Booking</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($title == 'Data Pengeluaran' || $title == 'Tambah Pengeluaran' || $title == 'Edit Pengeluaran') ? 'active' : '' ?>" href="<?= site_url('admin/pengeluaran') ?>">
                    <i class="bi bi-cash-coin"></i>
                    <span>Pengeluaran</span>
                </a>
            </li>
            <div class="sidebar-heading text-uppercase fw-bold text-muted px-4 mt-4 mb-2">
                <span>Laporan</span>
            </div>
            <li class="nav-item">
                <a class="nav-link <?= ($title == 'Laporan' || $title == 'Laporan Karyawan' || $title == 'Laporan Pembayaran' || $title == 'Laporan Pendapatan Bulanan' || $title == 'Laporan Pendapatan Pertahun' || $title == 'Laporan Pendapatan Tahunan') ? 'active' : '' ?>" href="<?= site_url('admin/reports') ?>">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan</span>
                </a>
            </li>

            <!-- <li class="nav-item">
                <a class="nav-link <?= $title == 'Settings' ? 'active' : '' ?>" href="<?= site_url('admin/settings') ?>">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" id="btn-logout" href="javascript:void(0)">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
<?php endif; ?>

<?php if (session('role') == 'pimpinan'): ?>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="Awan Barbershop" onerror="this.src='https://ui-avatars.com/api/?name=Awan+Barbershop&background=A27B5C&color=fff'">
            <div>
                <h3>AWAN</h3>
                <p>Barbershop</p>
            </div>
        </div>
        <hr class="sidebar-divider">
        <ul class="nav flex-column">


            <li class="nav-item">
                <a class="nav-link <?= ($title == 'Laporan' || $title == 'Laporan Karyawan') ? 'active' : '' ?>" href="<?= site_url('admin/reports') ?>">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan</span>
                </a>
            </li>

            <!-- <li class="nav-item">
                <a class="nav-link <?= $title == 'Settings' ? 'active' : '' ?>" href="<?= site_url('admin/settings') ?>">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" id="btn-logout" href="javascript:void(0)">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
<?php endif; ?>