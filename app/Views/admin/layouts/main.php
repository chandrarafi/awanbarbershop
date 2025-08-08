<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?> - Awan Barbershop</title>

    <!-- Custom CSS -->
    <?= $this->include('admin/layouts/style') ?>
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <?= $this->renderSection('modal') ?>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <?= $this->include('admin/layouts/sidebar') ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <?= $this->include('admin/layouts/topbar') ?>

        <!-- Page Content -->
        <div class="container-fluid animate__animated animate__fadeIn">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <?= $this->include('admin/layouts/script') ?>
    <?= $this->renderSection('scripts') ?>
</body>

</html>