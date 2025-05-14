<?php

namespace App\Models;

use CodeIgniter\Model;

class PaketModel extends Model
{
    protected $table            = 'paket';
    protected $primaryKey       = 'idpaket';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idpaket', 'namapaket', 'deskripsi', 'harga'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function __construct()
    {
        parent::__construct();
        $this->initValidationRules();
    }

    protected function initValidationRules()
    {
        $this->validationRules = [
            'idpaket' => [
                'rules' => 'required|max_length[10]|is_unique[paket.idpaket,idpaket,{idpaket}]',
                'errors' => [
                    'required' => 'ID Paket harus diisi',
                    'max_length' => 'ID Paket maksimal 10 karakter',
                    'is_unique' => 'ID Paket sudah ada dalam database'
                ]
            ],
            'namapaket' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama Paket harus diisi',
                    'min_length' => 'Nama Paket minimal 3 karakter',
                    'max_length' => 'Nama Paket maksimal 100 karakter'
                ]
            ],
            'deskripsi' => [
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Deskripsi harus diisi',
                    'min_length' => 'Deskripsi minimal 10 karakter'
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Harga harus diisi',
                    'numeric' => 'Harga harus berupa angka',
                    'greater_than' => 'Harga harus lebih besar dari 0'
                ]
            ]
        ];
    }

    public function generateId()
    {
        $lastId = $this->select('idpaket')
            ->orderBy('idpaket', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();

        if (!$lastId) {
            return 'PKT001';
        }

        $lastNumber = (int) substr($lastId->idpaket, 3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return 'PKT' . $newNumber;
    }

    public function save($data): bool
    {
        // Jika ini adalah update, modifikasi aturan is_unique
        if (!empty($data['idpaket'])) {
            $this->validationRules['idpaket']['rules'] = 'required|max_length[10]|is_unique[paket.idpaket,idpaket,' . $data['idpaket'] . ']';
        }

        return parent::save($data);
    }
}
