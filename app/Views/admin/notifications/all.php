<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .notification-card {
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
        margin-bottom: 15px;
    }

    .notification-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .notification-card .card-body {
        padding: 15px;
    }

    .notification-unread {
        background-color: rgba(13, 110, 253, 0.05);
        border-left: 4px solid #A27B5C;
    }

    .notification-read {
        opacity: 0.85;
    }

    .notification-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .notification-icon i {
        font-size: 1.5rem;
        color: #A27B5C;
    }

    .notification-title {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .notification-message {
        color: #6c757d;
        margin-bottom: 5px;
    }

    .notification-time {
        font-size: 0.8rem;
        color: #adb5bd;
    }

    .notification-unread-dot {
        width: 10px;
        height: 10px;
        background-color: #A27B5C;
        border-radius: 50%;
        display: inline-block;
        margin-left: 5px;
    }

    .notification-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
    }

    .notification-actions .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }

    .empty-state {
        text-align: center;
        padding: 50px 0;
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        color: #6c757d;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #adb5bd;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Semua Notifikasi</h2>
                <button id="markAllReadBtn" class="btn btn-outline-primary">
                    <i class="bi bi-check-all me-1"></i> Tandai Semua Dibaca
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <?php if (empty($notifications)) : ?>
                <div class="empty-state">
                    <i class="bi bi-bell-slash"></i>
                    <h4>Tidak Ada Notifikasi</h4>
                    <p>Anda tidak memiliki notifikasi saat ini.</p>
                </div>
            <?php else : ?>
                <?php foreach ($notifications as $notification) : ?>
                    <?php
                    $isRead = (int)$notification['is_read'] === 1;
                    $cardClass = $isRead ? 'notification-read' : 'notification-unread';
                    $iconClass = 'bi-bell';

                    // Sesuaikan icon berdasarkan jenis notifikasi
                    if ($notification['type'] === 'booking_baru') {
                        $iconClass = 'bi-calendar-plus';
                    } elseif ($notification['type'] === 'pembayaran') {
                        $iconClass = 'bi-credit-card';
                    }
                    ?>
                    <div class="card notification-card <?= $cardClass ?>">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="notification-icon">
                                    <i class="bi <?= $iconClass ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="notification-title">
                                            <?= $notification['title'] ?>
                                            <?php if (!$isRead) : ?>
                                                <span class="notification-unread-dot"></span>
                                            <?php endif; ?>
                                        </h5>
                                        <small class="notification-time"><?= $notification['time_ago'] ?></small>
                                    </div>
                                    <p class="notification-message"><?= $notification['message'] ?></p>
                                    <div class="notification-actions">
                                        <?php if (!$isRead) : ?>
                                            <button class="btn btn-sm btn-outline-secondary mark-read-btn me-2" data-id="<?= $notification['id'] ?>">
                                                <i class="bi bi-check"></i> Tandai Dibaca
                                            </button>
                                        <?php endif; ?>
                                        <a href="<?= site_url('admin/notifications/view/' . $notification['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Tandai notifikasi sebagai dibaca
        $('.mark-read-btn').on('click', function() {
            const notificationId = $(this).data('id');
            const notificationCard = $(this).closest('.notification-card');

            $.ajax({
                url: '<?= site_url('admin/notifications/mark-read') ?>',
                type: 'POST',
                data: {
                    id: notificationId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Ubah tampilan kartu
                        notificationCard.removeClass('notification-unread').addClass('notification-read');
                        notificationCard.find('.notification-unread-dot').remove();
                        notificationCard.find('.mark-read-btn').remove();

                        // Tampilkan feedback
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Notifikasi telah ditandai sebagai dibaca',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                }
            });
        });

        // Tandai semua notifikasi sebagai dibaca
        $('#markAllReadBtn').on('click', function() {
            $.ajax({
                url: '<?= site_url('admin/notifications/mark-all-read') ?>',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Ubah tampilan semua kartu
                        $('.notification-card').removeClass('notification-unread').addClass('notification-read');
                        $('.notification-unread-dot').remove();
                        $('.mark-read-btn').remove();

                        // Tampilkan feedback
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Semua notifikasi telah ditandai sebagai dibaca',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>