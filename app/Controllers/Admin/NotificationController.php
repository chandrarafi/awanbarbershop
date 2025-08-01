<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Mendapatkan daftar notifikasi yang belum dibaca
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function getUnreadNotifications()
    {
        // Untuk admin, kita tidak perlu filter berdasarkan idpelanggan
        // Semua notifikasi akan ditampilkan (yang idpelanggan=null atau semua idpelanggan)
        $notifications = $this->notificationModel->getUnreadNotifications(null, 10);
        $count = $this->notificationModel->countUnreadNotifications(null);

        // Debug
        log_message('debug', 'Notifications count: ' . $count);
        log_message('debug', 'Notifications data: ' . json_encode($notifications));

        // Format waktu notifikasi lebih user-friendly
        foreach ($notifications as &$notification) {
            // Pastikan semua field yang diperlukan ada
            if (!isset($notification['title'])) {
                $notification['title'] = 'Notifikasi';
            }

            if (!isset($notification['message'])) {
                $notification['message'] = 'Tidak ada pesan';
            }

            $createdAt = new \DateTime($notification['created_at']);
            $now = new \DateTime();
            $interval = $now->diff($createdAt);

            if ($interval->days > 0) {
                if ($interval->days == 1) {
                    $notification['time_ago'] = '1 hari yang lalu';
                } else {
                    $notification['time_ago'] = $interval->days . ' hari yang lalu';
                }
            } elseif ($interval->h > 0) {
                if ($interval->h == 1) {
                    $notification['time_ago'] = '1 jam yang lalu';
                } else {
                    $notification['time_ago'] = $interval->h . ' jam yang lalu';
                }
            } elseif ($interval->i > 0) {
                if ($interval->i == 1) {
                    $notification['time_ago'] = '1 menit yang lalu';
                } else {
                    $notification['time_ago'] = $interval->i . ' menit yang lalu';
                }
            } else {
                $notification['time_ago'] = 'Baru saja';
            }
        }

        // Debug setelah format
        log_message('debug', 'Formatted notifications: ' . json_encode($notifications));

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'notifications' => $notifications,
                'count' => $count
            ]
        ]);
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function markAsRead()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $id = $this->request->getPost('id');

        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'ID notifikasi tidak valid'
            ]);
        }

        $success = $this->notificationModel->markAsRead($id);

        return $this->response->setJSON([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? 'Notifikasi telah dibaca' : 'Gagal menandai notifikasi sebagai dibaca'
        ]);
    }

    /**
     * Menandai semua notifikasi sebagai sudah dibaca
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function markAllAsRead()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        // Untuk admin, kita tidak perlu filter berdasarkan idpelanggan
        $success = $this->notificationModel->markAllAsRead(null);

        return $this->response->setJSON([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? 'Semua notifikasi telah dibaca' : 'Gagal menandai semua notifikasi sebagai dibaca'
        ]);
    }

    /**
     * Detail notifikasi dan redirect ke halaman terkait
     * 
     * @param int $id ID notifikasi
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function viewDetail($id)
    {
        // Ambil data notifikasi
        $notification = $this->notificationModel->find($id);

        if (!$notification) {
            return redirect()->back()->with('error', 'Notifikasi tidak ditemukan');
        }

        // Tandai notifikasi sebagai sudah dibaca
        $this->notificationModel->markAsRead($id);

        // Redirect ke halaman yang sesuai berdasarkan jenis notifikasi
        if ($notification['type'] === 'booking_baru') {
            return redirect()->to(site_url('admin/booking/show/' . $notification['reference_id']));
        }

        if ($notification['type'] === 'pembayaran_baru') {
            return redirect()->to(site_url('admin/booking/show/' . $notification['reference_id']));
        }

        if ($notification['type'] === 'booking_baru') {
            return redirect()->to(site_url('admin/booking/show/' . $notification['reference_id']));
        }
        // Default redirect ke dashboard jika tipe notifikasi tidak dikenali
        return redirect()->to(site_url('admin/dashboard'));
    }

    /**
     * Membuat notifikasi test untuk debugging
     */
    public function createTest()
    {
        // Buat notifikasi test
        $result = $this->notificationModel->createNotification(
            'booking_baru',
            'Test Notifikasi Admin',
            'Ini adalah notifikasi test untuk debugging dari admin',
            'TEST-ADMIN-123',
            null
        );

        return $this->response->setJSON([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Notifikasi test berhasil dibuat' : 'Gagal membuat notifikasi test'
        ]);
    }

    /**
     * Melihat semua notifikasi yang ada
     */
    public function viewAll()
    {
        // Ambil semua notifikasi
        $builder = $this->notificationModel->builder();
        $notifications = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'count' => count($notifications),
            'notifications' => $notifications
        ]);
    }

    /**
     * Menampilkan halaman semua notifikasi
     */
    public function allNotifications()
    {
        // Ambil semua notifikasi
        $builder = $this->notificationModel->builder();
        $builder->orderBy('created_at', 'DESC');
        $notifications = $builder->get()->getResultArray();

        // Format waktu notifikasi
        foreach ($notifications as &$notification) {
            // Pastikan semua field yang diperlukan ada
            if (!isset($notification['title'])) {
                $notification['title'] = 'Notifikasi';
            }

            if (!isset($notification['message'])) {
                $notification['message'] = 'Tidak ada pesan';
            }

            $createdAt = new \DateTime($notification['created_at']);
            $now = new \DateTime();
            $interval = $now->diff($createdAt);

            if ($interval->days > 0) {
                if ($interval->days == 1) {
                    $notification['time_ago'] = '1 hari yang lalu';
                } else {
                    $notification['time_ago'] = $interval->days . ' hari yang lalu';
                }
            } elseif ($interval->h > 0) {
                if ($interval->h == 1) {
                    $notification['time_ago'] = '1 jam yang lalu';
                } else {
                    $notification['time_ago'] = $interval->h . ' jam yang lalu';
                }
            } elseif ($interval->i > 0) {
                if ($interval->i == 1) {
                    $notification['time_ago'] = '1 menit yang lalu';
                } else {
                    $notification['time_ago'] = $interval->i . ' menit yang lalu';
                }
            } else {
                $notification['time_ago'] = 'Baru saja';
            }
        }

        $data = [
            'title' => 'Semua Notifikasi',
            'notifications' => $notifications
        ];

        return view('admin/notifications/all', $data);
    }
}
