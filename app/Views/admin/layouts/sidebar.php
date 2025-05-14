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
            <a class="nav-link <?= $title == 'Manajemen Paket' ? 'active' : '' ?>" href="<?= site_url('admin/paket') ?>">
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
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Services' ? 'active' : '' ?>" href="<?= site_url('admin/services') ?>">
                <i class="bi bi-scissors"></i>
                <span>Services</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Bookings' ? 'active' : '' ?>" href="<?= site_url('admin/bookings') ?>">
                <i class="bi bi-calendar-check"></i>
                <span>Bookings</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Reports' ? 'active' : '' ?>" href="<?= site_url('admin/reports') ?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Reports</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Settings' ? 'active' : '' ?>" href="<?= site_url('admin/settings') ?>">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="btn-logout" href="javascript:void(0)">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>