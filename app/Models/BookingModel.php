<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'booking';
    protected $primaryKey       = 'kdbooking';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kdbooking',
        'idpelanggan',
        'status',
        'jenispembayaran',
        'jumlahbayar',
        'total',
        'idkaryawan',
        'tanggal_booking',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
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
            'kdbooking' => [
                'rules' => 'required|max_length[20]',
                'errors' => [
                    'required' => 'Kode booking harus diisi',
                    'max_length' => 'Kode booking maksimal 20 karakter'
                ]
            ],
            'idpelanggan' => [
                'rules' => 'required|max_length[20]',
                'errors' => [
                    'required' => 'ID Pelanggan harus diisi',
                    'max_length' => 'ID Pelanggan maksimal 20 karakter'
                ]
            ],
            'status' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Status harus diisi'
                ]
            ],
            'jenispembayaran' => [
                'rules' => 'required|in_list[DP,Lunas]',
                'errors' => [
                    'required' => 'Jenis pembayaran harus diisi',
                    'in_list' => 'Jenis pembayaran harus DP atau Lunas'
                ]
            ],
            'tanggal_booking' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal booking harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ]
        ];
    }

    /**
     * Generate kode booking baru
     * Format: BK-YYYYMMDD-XXXX (BK-Tahun Bulan Tanggal-Nomor Urut 4 digit)
     * 
     * @return string
     */
    public function generateBookingCode()
    {
        $prefix = 'BK-' . date('Ymd') . '-';

        // Cari kode booking terakhir dengan awalan yang sama
        $lastBooking = $this->like('kdbooking', $prefix, 'after')
            ->orderBy('kdbooking', 'DESC')
            ->first();

        if (!$lastBooking) {
            return $prefix . '0001';
        }

        // Ambil nomor urut dari kode booking terakhir
        $lastNumber = (int) substr($lastBooking['kdbooking'], -4);
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $newNumber;
    }

    /**
     * Override save method untuk menangani kasus khusus validasi kdbooking
     * 
     * @param array|object $data
     * @return bool
     */
    public function save($data): bool
    {
        // Jika sedang update, nonaktifkan validasi untuk menghindari masalah placeholder
        if (!empty($data['kdbooking']) && $this->find($data['kdbooking'])) {
            $this->skipValidation(true);
        }

        // Jika ini adalah data baru, pastikan kdbooking unik secara manual
        if (empty($data['id']) && !empty($data['kdbooking'])) {
            $existing = $this->find($data['kdbooking']);
            if ($existing) {
                $this->errors['kdbooking'] = 'Kode booking sudah ada dalam database';
                return false;
            }
        }

        return parent::save($data);
    }

    /**
     * Mendapatkan data booking dengan informasi pelanggan
     *
     * @param string|null $id
     * @return array
     */
    public function getBookingWithPelanggan($id = null)
    {
        $builder = $this->db->table('booking b')
            ->select('b.*, p.nama_lengkap, p.no_hp, p.alamat, u.email')
            ->join('pelanggan p', 'p.idpelanggan = b.idpelanggan', 'left')
            ->join('users u', 'u.id = p.user_id', 'left');

        if ($id !== null) {
            $builder->where('b.kdbooking', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Mendapatkan detail booking dengan informasi paket
     *
     * @param string $kdbooking
     * @return array
     */
    public function getBookingDetails($kdbooking)
    {
        return $this->db->table('detail_booking')
            ->where('kdbooking', $kdbooking)
            ->get()
            ->getResultArray();
    }

    /**
     * Menghitung jumlah booking berdasarkan status
     *
     * @param string $status
     * @return int
     */
    public function countBookingByStatus($status)
    {
        return $this->where('status', $status)->countAllResults();
    }

    /**
     * Mendapatkan data booking untuk dashboard
     *
     * @param string $period daily|weekly|monthly|yearly
     * @return array
     */
    public function getBookingStats($period = 'monthly')
    {
        $sql = "";

        switch ($period) {
            case 'daily':
                $sql = "SELECT DATE(created_at) as date, COUNT(*) as total 
                        FROM booking 
                        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                        GROUP BY DATE(created_at) 
                        ORDER BY date";
                break;
            case 'weekly':
                $sql = "SELECT YEARWEEK(created_at) as week, COUNT(*) as total 
                        FROM booking 
                        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 WEEK) 
                        GROUP BY YEARWEEK(created_at) 
                        ORDER BY week";
                break;
            case 'monthly':
                $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total 
                        FROM booking 
                        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) 
                        GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                        ORDER BY month";
                break;
            case 'yearly':
                $sql = "SELECT YEAR(created_at) as year, COUNT(*) as total 
                        FROM booking 
                        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 5 YEAR) 
                        GROUP BY YEAR(created_at) 
                        ORDER BY year";
                break;
        }

        return $this->db->query($sql)->getResultArray();
    }
}
