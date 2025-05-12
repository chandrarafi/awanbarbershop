<div class="topbar">
    <button class="navbar-toggler d-md-none me-3" id="navbarToggler" type="button">
        <i class="bi bi-list"></i>
    </button>
    <h1><?= $title ?? 'Dashboard' ?></h1>
    <div class="ms-auto d-flex align-items-center">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name=Admin&background=A27B5C&color=fff" alt="Admin" class="rounded-circle me-2" width="32" height="32">
                <span class="d-none d-md-inline text-dark">Admin</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="<?= site_url('admin/settings/profile') ?>"><i class="bi bi-person-circle me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="<?= site_url('admin/settings') ?>"><i class="bi bi-gear me-2"></i>Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#" id="btn-logout-dropdown"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</div>