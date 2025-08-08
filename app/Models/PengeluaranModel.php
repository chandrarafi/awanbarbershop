<?php

namespace App\Models;

use CodeIgniter\Model;

class PengeluaranModel extends Model
{
    protected $table            = 'pengeluaran';
    protected $primaryKey       = 'idpengeluaran';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idpengeluaran', 'tgl', 'keterangan', 'jumlah'];


    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    protected $validationRules      = [
        'tgl'        => 'required',
        'jumlah'     => 'required|numeric',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;


    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateID'];
    protected $beforeUpdate   = [];

    protected function generateID(array $data)
    {
        if (isset($data['data']['idpengeluaran'])) {
            return $data;
        }

        $date = date('Ymd');
        $lastID = $this->orderBy('idpengeluaran', 'DESC')->first();
        $nextID = 'PG-' . $date . '-0001';

        if ($lastID) {
            $lastNumber = substr($lastID['idpengeluaran'], -4);
            $nextNumber = intval($lastNumber) + 1;
            $nextID = 'PG-' . $date . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        $data['data']['idpengeluaran'] = $nextID;
        return $data;
    }
}
