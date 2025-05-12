<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?> - Awan Barbershop</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2C3639;
            --primary-gradient-start: #3F4E4F;
            --primary-gradient-end: #2C3639;
            --secondary-color: #A27B5C;
            --success-color: #2ecc71;
            --info-color: #3498db;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #DCD7C9;
            --dark-color: #2C3639;
            --border-radius: 0.75rem;
            --card-border-radius: 1rem;
            --box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            color: #444;
            transition: all 0.3s ease;
            overflow-x: hidden;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-color);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* Sidebar styling */
        .sidebar {
            min-height: 100vh;
            background: var(--primary-color);
            box-shadow: var(--box-shadow);
            z-index: 1040;
            position: fixed;
            width: 280px;
            transition: all 0.3s ease-in-out;
        }

        .sidebar-brand {
            height: 5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            background: var(--secondary-color);
        }

        .sidebar-brand img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin-right: 10px;
            background: white;
            padding: 5px;
        }

        .sidebar-brand h3 {
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0;
            letter-spacing: 1px;
        }

        .sidebar-brand p {
            color: var(--light-color);
            margin-bottom: 0;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 0 1.5rem;
        }

        .nav-header {
            color: var(--light-color);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1.5rem;
            padding-left: 1.5rem;
        }

        .nav-item {
            position: relative;
            padding: 0 0.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            padding: 1rem;
            border-radius: var(--border-radius);
            margin: 0.2rem 0;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background-color: var(--secondary-color);
            transform: scaleY(0);
            transition: transform 0.3s, opacity 0.3s;
            transform-origin: top;
            opacity: 0;
            border-radius: 0 2px 2px 0;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-link:hover::before {
            transform: scaleY(1);
            opacity: 1;
        }

        .nav-link.active {
            background-color: var(--secondary-color);
            color: white;
            box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.1);
        }

        .nav-link.active::before {
            transform: scaleY(1);
            opacity: 1;
        }

        .nav-link i {
            margin-right: 0.8rem;
            font-size: 1.1rem;
            width: 1.5rem;
            text-align: center;
            transition: all 0.3s;
        }

        /* Main content */
        .main-content {
            margin-left: 280px;
            transition: all 0.3s ease-in-out;
            min-height: 100vh;
            background-color: #f5f5f5;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        /* Topbar */
        .topbar {
            height: 4.375rem;
            background-color: white;
            box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            border-radius: var(--card-border-radius);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .topbar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--secondary-color);
        }

        .topbar h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: var(--card-border-radius);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.07);
            transition: all 0.3s ease-in-out;
            background-color: white;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(58, 59, 69, 0.15);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        /* Buttons */
        .btn {
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            border-radius: var(--border-radius);
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--secondary-color);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Tables */
        .table-responsive {
            border-radius: var(--border-radius);
            background: white;
            padding: 1rem;
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fc;
            color: var(--primary-color);
            padding: 1rem;
            white-space: nowrap;
            border-bottom: 2px solid var(--secondary-color);
        }

        .table td {
            vertical-align: middle;
            padding: 1rem;
            color: #555;
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background-color: rgba(162, 123, 92, 0.05);
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: var(--card-border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
            border-left: 4px solid var(--secondary-color);
        }

        .stat-card .icon {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3rem;
            opacity: 0.1;
            color: var(--primary-color);
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: #666;
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 75px;
            }

            .sidebar-brand {
                padding: 1rem;
            }

            .sidebar-brand img {
                margin-right: 0;
            }

            .sidebar-brand h3,
            .sidebar-brand p,
            .nav-header,
            .nav-link span {
                display: none;
            }

            .nav-link {
                padding: 1rem;
                text-align: center;
            }

            .nav-link i {
                margin: 0;
                font-size: 1.2rem;
            }

            .main-content {
                margin-left: 75px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
                width: 250px;
            }

            .sidebar.show .sidebar-brand h3,
            .sidebar.show .sidebar-brand p,
            .sidebar.show .nav-header,
            .sidebar.show .nav-link span {
                display: inline-block;
            }

            .topbar {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<body>

    <?= $this->include('admin/layouts/sidebar') ?>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
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

        <!-- Page Content -->
        <div class="container-fluid animate__animated animate__fadeIn">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        $(document).ready(function() {
            // Toggle sidebar on mobile
            $('#navbarToggler').on('click', function() {
                $('#sidebar').toggleClass('show');
            });

            // Close sidebar when clicking outside
            $(document).on('click', function(e) {
                if ($(window).width() < 768) {
                    if (!$(e.target).closest('#sidebar').length &&
                        !$(e.target).closest('#navbarToggler').length &&
                        $('#sidebar').hasClass('show')) {
                        $('#sidebar').removeClass('show');
                    }
                }
            });

            // Logout functionality
            function handleLogout() {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan keluar dari sistem",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#A27B5C',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Keluar!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= site_url('auth/logout') ?>';
                    }
                });
            }

            $('#btn-logout, #btn-logout-dropdown').on('click', handleLogout);
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>