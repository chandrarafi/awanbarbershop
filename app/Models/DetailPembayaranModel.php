<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPembayaranModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'detail_pembayaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'kdbayar', 'total_bayar', 'grandtotal', 'metode', 'status', 'created_at', 'updated_at'];


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
            'kdbayar' => [
                'rules' => 'required|max_length[20]',
                'errors' => [
                    'required' => 'Kode pembayaran harus diisi',
                    'max_length' => 'Kode pembayaran maksimal 20 karakter'
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
                'rules' => 'required',
                'errors' => [
                    'required' => 'Status pembayaran harus diisi'
                ]
            ]
        ];
    }

    /**
     * Mendapatkan detail pembayaran berdasarkan kode pembayaran
     *
     * @param string $kdbayar
     * @return array
     */
    public function getDetailsByKodeBayar($kdbayar)
    {
        return $this->where('kdbayar', $kdbayar)
            ->findAll();
    }

    /**
     * Mendapatkan total pembayaran berdasarkan kode pembayaran
     *
     * @param string $kdbayar
     * @return float
     */
    public function getTotalByKodeBayar($kdbayar)
    {
        $result = $this->selectSum('total_bayar')
            ->where('kdbayar', $kdbayar)
            ->first();

        return $result['total_bayar'] ?? 0;
    }

    /**
     * Mendapatkan detail pembayaran dengan informasi booking
     *
     * @param int $id
     * @return array
     */
    public function getDetailWithBooking($id)
    {
        return $this->db->table('detail_pembayaran dp')
            ->select('dp.*, p.fakturbooking, b.idpelanggan, b.tanggal_booking')
            ->join('pembayaran p', 'p.id = dp.kdbayar', 'left')
            ->join('booking b', 'b.kdbooking = p.fakturbooking', 'left')
            ->where('dp.id', $id)
            ->get()
            ->getRowArray();
    }
}
