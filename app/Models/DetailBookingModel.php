<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailBookingModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'detail_booking';
    protected $primaryKey       = 'iddetail';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'iddetail',
        'tgl',
        'kdbooking',
        'kdpaket',
        'nama_paket',
        'deskripsi',
        'harga',
        'jamstart',
        'jamend',
        'status',
        'idkaryawan'
    ];


    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = true; // Menonaktifkan validasi untuk menghindari masalah placeholder
    protected $cleanValidationRules = true;

    public function __construct()
    {
        parent::__construct();
        $this->initValidationRules();
    }

    protected function initValidationRules()
    {
        $this->validationRules = [
            'iddetail' => [
                'rules' => 'required|max_length[20]',
                'errors' => [
                    'required' => 'ID Detail harus diisi',
                    'max_length' => 'ID Detail maksimal 20 karakter'
                ]
            ],
            'kdbooking' => [
                'rules' => 'required|max_length[20]',
                'errors' => [
                    'required' => 'Kode booking harus diisi',
                    'max_length' => 'Kode booking maksimal 20 karakter'
                ]
            ],
            'kdpaket' => [
                'rules' => 'required|max_length[25]',
                'errors' => [
                    'required' => 'Kode paket harus diisi',
                    'max_length' => 'Kode paket maksimal 25 karakter'
                ]
            ],
            'nama_paket' => [
                'rules' => 'required|max_length[30]',
                'errors' => [
                    'required' => 'Nama paket harus diisi',
                    'max_length' => 'Nama paket maksimal 30 karakter'
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Harga harus diisi',
                    'numeric' => 'Harga harus berupa angka',
                    'greater_than' => 'Harga harus lebih besar dari 0'
                ]
            ],
            'tgl' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'jamstart' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam mulai harus diisi'
                ]
            ],
            'jamend' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam selesai harus diisi'
                ]
            ],
            'status' => [
                'rules' => 'required|in_list[1,2,3,4]',
                'errors' => [
                    'required' => 'Status harus diisi',
                    'in_list' => 'Status tidak valid'
                ]
            ]
        ];
    }

    /**
     * Generate ID detail booking baru
     * Format: DTL-YYYYMMDD-XXXX (DTL-Tahun Bulan Tanggal-Nomor Urut 4 digit)
     * 
     * @return string
     */
    public function generateDetailId()
    {
        $prefix = 'DTL-' . date('Ymd') . '-';


        $lastDetail = $this->like('iddetail', $prefix, 'after')
            ->orderBy('iddetail', 'DESC')
            ->first();

        if (!$lastDetail) {
            return $prefix . '0001';
        }


        $lastNumber = (int) substr($lastDetail['iddetail'], -4);
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $newNumber;
    }

    /**
     * Mendapatkan detail booking berdasarkan kode booking
     *
     * @param string $kdbooking
     * @return array
     */
    public function getDetailsByBookingCode($kdbooking)
    {
        return $this->db->table('detail_booking db')
            ->select('db.*, k.namakaryawan as nama_karyawan')
            ->join('karyawan k', 'k.idkaryawan = db.idkaryawan', 'left')
            ->where('db.kdbooking', $kdbooking)
            ->get()
            ->getResultArray();
    }

    /**
     * Mendapatkan detail booking dengan informasi karyawan
     *
     * @param string $iddetail
     * @return array
     */
    public function getDetailWithKaryawan($iddetail)
    {
        return $this->db->table('detail_booking db')
            ->select('db.*, k.namakaryawan')
            ->join('karyawan k', 'k.idkaryawan = db.idkaryawan', 'left')
            ->where('db.iddetail', $iddetail)
            ->get()
            ->getRowArray();
    }

    /**
     * Mengecek ketersediaan jadwal karyawan
     *
     * @param string $idkaryawan
     * @param string $tanggal
     * @param string $jamstart
     * @param string $jamend
     * @param string|null $iddetail ID detail booking yang sedang diedit (opsional)
     * @return bool true jika tersedia, false jika sudah terisi
     */
    public function checkKaryawanAvailability($idkaryawan, $tanggal, $jamstart, $jamend, $iddetail = null)
    {
        $builder = $this->db->table('detail_booking')
            ->where('idkaryawan', $idkaryawan)
            ->where('tgl', $tanggal)
            ->where('status !=', '4') // 4 = Dibatalkan
            ->groupStart()
            ->where("(jamstart < '$jamend' AND jamend > '$jamstart')")
            ->groupEnd();


        if ($iddetail !== null) {
            $builder->where('iddetail !=', $iddetail);
        }

        $count = $builder->countAllResults();

        return $count === 0;
    }

    /**
     * Mendapatkan jadwal booking untuk karyawan
     *
     * @param string $idkaryawan
     * @param string $tanggal
     * @return array
     */
    public function getKaryawanSchedule($idkaryawan, $tanggal)
    {
        return $this->db->table('detail_booking db')
            ->select('db.*, b.status as booking_status')
            ->join('booking b', 'b.kdbooking = db.kdbooking', 'left')
            ->join('pelanggan p', 'p.idpelanggan = b.idpelanggan', 'left')
            ->where('db.idkaryawan', $idkaryawan)
            ->where('db.tgl', $tanggal)
            ->where('db.status !=', '4') // 4 = Dibatalkan
            ->orderBy('db.jamstart', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Mendapatkan jumlah booking berdasarkan status
     *
     * @param int $status 1 = Pending, 2 = Confirmed, 3 = Completed, 4 = Cancelled
     * @return int
     */
    public function countByStatus($status)
    {
        return $this->where('status', $status)->countAllResults();
    }

    /**
     * Override save method untuk menangani kasus khusus validasi iddetail
     * 
     * @param array|object $data
     * @return bool
     */
    public function save($data): bool
    {

        if (!empty($data['iddetail']) && $this->find($data['iddetail'])) {
            $this->skipValidation(true);
        }


        if (!empty($data['iddetail'])) {
            $existing = $this->find($data['iddetail']);
            if ($existing) {
                $this->errors['iddetail'] = 'ID Detail sudah ada dalam database';
                return false;
            }
        }

        return parent::save($data);
    }
}
