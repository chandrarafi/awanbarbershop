<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'fakturbooking', 'kdpembayaran', 'total_bayar', 'grandtotal', 'metode', 'status', 'jenis', 'bukti', 'created_at', 'updated_at'];


    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function __construct()
    {
        parent::__construct();
        $this->initValidationRules();
    }

    protected function initValidationRules()
    {
        $this->validationRules = [
            'fakturbooking' => [
                'rules' => 'required|max_length[20]',
                'errors' => [
                    'required' => 'Kode booking harus diisi',
                    'max_length' => 'Kode booking maksimal 20 karakter'
                ]
            ],
            'total_bayar' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Total bayar harus diisi',
                    'numeric' => 'Total bayar harus berupa angka',
                    'greater_than' => 'Total bayar harus lebih besar dari 0'
                ]
            ],
            'grandtotal' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Grand total harus diisi',
                    'numeric' => 'Grand total harus berupa angka',
                    'greater_than' => 'Grand total harus lebih besar dari 0'
                ]
            ],
            'metode' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Metode pembayaran harus diisi'
                ]
            ],
            'status' => [
                'rules' => 'required|in_list[pending,paid,cancelled,failed]',
                'errors' => [
                    'required' => 'Status pembayaran harus diisi',
                    'in_list' => 'Status pembayaran tidak valid (pending, paid, cancelled, failed)'
                ]
            ]
        ];
    }

    /**
     * Override update method untuk mendukung perubahan status dengan lebih baik
     * 
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id = null, $data = null): bool
    {

        if (is_array($data) && count($data) === 1 && isset($data['status'])) {
            $this->skipValidation(true);
        }

        return parent::update($id, $data);
    }

    /**
     * Method khusus untuk update status pembayaran
     * 
     * @param int $id ID pembayaran
     * @param string $status Status baru (paid, pending, cancelled, failed)
     * @return bool
     */
    public function updateStatus($id, string $status): bool
    {

        $validStatus = ['pending', 'paid', 'cancelled', 'failed'];
        if (!in_array($status, $validStatus)) {
            return false;
        }

        $this->skipValidation(true);
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Method untuk update semua status pembayaran berdasarkan kode booking
     * 
     * @param string $fakturbooking Kode Booking
     * @param string $status Status baru
     * @return bool
     */
    public function updateStatusByBookingCode(string $fakturbooking, string $status): bool
    {

        $validStatus = ['pending', 'paid', 'cancelled', 'failed'];
        if (!in_array($status, $validStatus)) {
            return false;
        }

        $this->skipValidation(true);
        return $this->where('fakturbooking', $fakturbooking)
            ->set(['status' => $status])
            ->update();
    }

    /**
     * Generate faktur pembayaran
     * Format: INV-YYYYMMDD-XXXX (INV-Tahun Bulan Tanggal-Nomor Urut 4 digit)
     * 
     * @return string
     */
    public function generateInvoiceNumber()
    {
        $prefix = 'INV-' . date('Ymd') . '-';


        $lastInvoice = $this->like('fakturbooking', $prefix, 'after')
            ->orderBy('fakturbooking', 'DESC')
            ->first();

        if (!$lastInvoice) {
            return $prefix . '0001';
        }


        $lastNumber = (int) substr($lastInvoice['fakturbooking'], -4);
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $newNumber;
    }

    /**
     * Generate kode pembayaran
     * Format: PAY-YYYYMMDD-XXXX (PAY-Tahun Bulan Tanggal-Nomor Urut 4 digit)
     * 
     * @return string
     */
    public function generatePaymentCode()
    {
        $prefix = 'PAY-' . date('Ymd') . '-';

        try {

            $query = $this->db->table($this->table)
                ->like('kdpembayaran', $prefix, 'after')
                ->orderBy('kdpembayaran', 'DESC')
                ->limit(1);

            $result = $query->get();

            if ($result && $result->getNumRows() > 0) {
                $lastPayment = $result->getRowArray();

                $lastNumber = (int) substr($lastPayment['kdpembayaran'], -4);
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }

            return $prefix . $newNumber;
        } catch (\Exception $e) {
            log_message('error', 'Error generating payment code: ' . $e->getMessage());

            return $prefix . '0001-' . uniqid();
        }
    }

    /**
     * Get all payment data by booking code
     * 
     * @param string $fakturbooking
     * @return array
     */
    public function getPembayaranByBookingCode($fakturbooking)
    {
        return $this->where('fakturbooking', $fakturbooking)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

    /**
     * Mendapatkan data pembayaran dengan informasi booking
     *
     * @param int $id
     * @return array
     */
    public function getPembayaranWithBooking($id)
    {
        return $this->db->table('pembayaran p')
            ->select('p.*, b.idpelanggan, b.status as booking_status, b.tanggal_booking')
            ->join('booking b', 'b.kdbooking = p.fakturbooking', 'left')
            ->where('p.id', $id)
            ->get()
            ->getRowArray();
    }

    /**
     * Mendapatkan total pendapatan berdasarkan periode
     *
     * @param string $period daily, weekly, monthly, yearly
     * @return array
     */
    public function getRevenueStats($period = 'monthly')
    {
        $sql = "";

        switch ($period) {
            case 'daily':
                $sql = "SELECT DATE(created_at) as date, SUM(total_bayar) as total 
                        FROM pembayaran 
                        WHERE status = 'paid' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                        GROUP BY DATE(created_at) 
                        ORDER BY date";
                break;
            case 'weekly':
                $sql = "SELECT YEARWEEK(created_at) as week, SUM(total_bayar) as total 
                        FROM pembayaran 
                        WHERE status = 'paid' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 12 WEEK) 
                        GROUP BY YEARWEEK(created_at) 
                        ORDER BY week";
                break;
            case 'monthly':
                $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_bayar) as total 
                        FROM pembayaran 
                        WHERE status = 'paid' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) 
                        GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                        ORDER BY month";
                break;
            case 'yearly':
                $sql = "SELECT YEAR(created_at) as year, SUM(total_bayar) as total 
                        FROM pembayaran 
                        WHERE status = 'paid' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 5 YEAR) 
                        GROUP BY YEAR(created_at) 
                        ORDER BY year";
                break;
        }

        return $this->db->query($sql)->getResultArray();
    }

    /**
     * Mendapatkan total pendapatan hari ini
     *
     * @return float
     */
    public function getTodayRevenue()
    {
        $result = $this->selectSum('total_bayar')
            ->where('status', 'paid')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->first();

        return $result['total_bayar'] ?? 0;
    }

    /**
     * Mendapatkan total pendapatan bulan ini
     *
     * @return float
     */
    public function getMonthRevenue()
    {
        $result = $this->selectSum('total_bayar')
            ->where('status', 'paid')
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->first();

        return $result['total_bayar'] ?? 0;
    }
}
