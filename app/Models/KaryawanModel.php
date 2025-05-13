<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'karyawan';
    protected $primaryKey       = 'idkaryawan';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idkaryawan', 'namakaryawan', 'alamat', 'nohp'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [];
    protected $validationMessages = [];
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
            'idkaryawan' => [
                'rules' => 'required|max_length[10]|is_unique[karyawan.idkaryawan]',
                'errors' => [
                    'required' => 'ID Karyawan harus diisi',
                    'max_length' => 'ID Karyawan maksimal 10 karakter',
                    'is_unique' => 'ID Karyawan sudah digunakan'
                ]
            ],
            'namakaryawan' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama Karyawan harus diisi',
                    'min_length' => 'Nama Karyawan minimal 3 karakter',
                    'max_length' => 'Nama Karyawan maksimal 100 karakter'
                ]
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat harus diisi'
                ]
            ],
            'nohp' => [
                'rules' => 'required|max_length[15]|numeric',
                'errors' => [
                    'max_length' => 'Nomor HP maksimal 15 karakter',
                    'numeric' => 'Nomor HP hanya boleh berisi angka',
                    'required' => 'Nomor HP harus diisi'
                ]
            ],

        ];
    }

    public function generateId()
    {
        $prefix = 'KRY';
        $lastId = $this->select('idkaryawan')
            ->orderBy('idkaryawan', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();

        if (!$lastId) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($lastId->idkaryawan, 3);
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $newNumber;
    }

    public function save($data): bool
    {
        // Jika ini adalah update, modifikasi aturan is_unique
        if (!empty($data['idkaryawan'])) {
            $this->validationRules['idkaryawan']['rules'] = 'required|max_length[10]|is_unique[karyawan.idkaryawan,idkaryawan,' . $data['idkaryawan'] . ']';
        }

        return parent::save($data);
    }
}
