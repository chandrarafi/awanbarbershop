<div class="topbar">
    <button class="navbar-toggler d-lg-none me-2 me-sm-3" id="navbarToggler" type="button">
        <i class="bi bi-list"></i>
    </button>
    <h1 class="mb-0"><?= $title ?? 'Dashboard' ?></h1>
    <div class="ms-auto d-flex align-items-center">
        <!-- Notifikasi -->
        <div class="dropdown me-2 me-sm-3">
            <a href="#" class="position-relative text-decoration-none d-flex align-items-center justify-content-center" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px;">
                <i class="bi bi-bell fs-6 fs-sm-5"></i>
                <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                    <span class="notification-count">0</span>
                    <span class="visually-hidden">unread notifications</span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow notification-dropdown p-0" aria-labelledby="notificationDropdown" data-bs-popper="static">
                <div class="p-2 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fs-6">Notifikasi</h6>
                    <button id="markAllReadBtn" class="btn btn-sm btn-link text-decoration-none p-1 fs-7">Tandai semua dibaca</button>
                </div>
                <div id="notificationList" class="p-0">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="p-2 bg-light border-top text-center">
                    <a href="#" class="btn btn-sm btn-link text-decoration-none fs-7" id="viewAllNotifications">Lihat semua notifikasi</a>
                </div>
            </div>
        </div>

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name=Admin&background=A27B5C&color=fff" alt="Admin" class="rounded-circle me-1 me-sm-2" width="32" height="32">
                <span class="d-none d-sm-inline text-dark fs-6">Admin</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                <li><a class="dropdown-item fs-6" href="<?= site_url('admin/settings/profile') ?>"><i class="bi bi-person-circle me-2"></i>Profile</a></li>
                <li><a class="dropdown-item fs-6" href="<?= site_url('admin/settings') ?>"><i class="bi bi-gear me-2"></i>Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item fs-6" href="#" id="btn-logout-dropdown"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</div>