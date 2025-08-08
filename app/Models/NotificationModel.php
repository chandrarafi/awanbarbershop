<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'type',
        'title',
        'message',
        'reference_id',
        'idpelanggan',
        'is_read',
        'created_at',
        'updated_at'
    ];


    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Create a new notification
     * 
     * @param string $type Notification type (booking_baru, pembayaran, etc)
     * @param string $title Notification title
     * @param string $message Notification message
     * @param string $referenceId Related ID (kdbooking, etc)
     * @param string|null $idPelanggan Specific pelanggan ID or null for all admins
     * @return bool
     */
    public function createNotification($type, $title, $message, $referenceId = null, $idPelanggan = null)
    {
        $data = [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'reference_id' => $referenceId,
            'idpelanggan' => $idPelanggan,
            'is_read' => 0,
        ];

        return $this->insert($data);
    }

    /**
     * Create booking notification for all admins
     * 
     * @param string $kdbooking Booking code
     * @param string $customerName Customer name
     * @param string $idPelanggan Pelanggan ID
     * @return bool
     */
    public function createBookingNotification($kdbooking, $customerName, $idPelanggan = null)
    {

        $type = 'booking_baru';


        $title = 'Booking Baru';
        $message = "Booking baru oleh {$customerName} dengan kode {$kdbooking}";

        return $this->createNotification($type, $title, $message, $kdbooking, $idPelanggan);
    }

    /**
     * Get unread notifications for a user
     * 
     * @param string $idPelanggan Pelanggan ID
     * @param int $limit Number of notifications to retrieve
     * @return array
     */
    public function getUnreadNotifications($idPelanggan = null, $limit = 10)
    {
        $builder = $this->builder();


        $builder->where('is_read', 0);


        if ($idPelanggan !== null) {
            $builder->groupStart()
                ->where('idpelanggan', $idPelanggan)
                ->orWhere('idpelanggan', null)
                ->groupEnd();
        }


        $builder->orderBy('created_at', 'DESC')
            ->limit($limit);


        log_message('debug', 'Notification query: ' . $builder->getCompiledSelect());

        $result = $builder->get()->getResultArray();


        log_message('debug', 'Notification result count: ' . count($result));

        return $result;
    }

    /**
     * Count unread notifications for a user
     * 
     * @param string $idPelanggan Pelanggan ID
     * @return int
     */
    public function countUnreadNotifications($idPelanggan = null)
    {
        $builder = $this->builder();


        $builder->where('is_read', 0);


        if ($idPelanggan !== null) {
            $builder->groupStart()
                ->where('idpelanggan', $idPelanggan)
                ->orWhere('idpelanggan', null)
                ->groupEnd();
        }

        return $builder->countAllResults();
    }

    /**
     * Mark notification as read
     * 
     * @param int $id Notification ID
     * @return bool
     */
    public function markAsRead($id)
    {
        return $this->update($id, ['is_read' => 1]);
    }

    /**
     * Mark all notifications as read for a user
     * 
     * @param string $idPelanggan Pelanggan ID
     * @return bool
     */
    public function markAllAsRead($idPelanggan = null)
    {
        $builder = $this->builder();

        if ($idPelanggan !== null) {
            $builder->groupStart()
                ->where('idpelanggan', $idPelanggan)
                ->orWhere('idpelanggan', null)
                ->groupEnd();
        }

        return $builder->update(['is_read' => 1]);
    }
}
