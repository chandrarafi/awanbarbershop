<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table            = 'pelanggan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'idpelanggan',
        'user_id',
        'nama_lengkap',
        'jeniskelamin',
        'alamat',
        'no_hp',
        'tanggal_lahir'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'idpelanggan' => 'required|is_unique[pelanggan.idpelanggan,id,{id}]',
        'nama_lengkap' => 'required|min_length[3]',
        'jeniskelamin' => 'permit_empty|in_list[Laki-laki,Perempuan]',
        'no_hp' => 'permit_empty|numeric|min_length[10]|max_length[15]',
        'tanggal_lahir' => 'permit_empty|valid_date',
        'alamat' => 'permit_empty|min_length[5]'
    ];

    protected $validationMessages = [
        'idpelanggan' => [
            'required' => 'ID Pelanggan harus diisi',
            'is_unique' => 'ID Pelanggan sudah digunakan'
        ],
        'nama_lengkap' => [
            'required' => 'Nama lengkap harus diisi',
            'min_length' => 'Nama lengkap minimal 3 karakter'
        ],
        'jeniskelamin' => [
            'in_list' => 'Jenis kelamin tidak valid'
        ],
        'no_hp' => [
            'numeric' => 'No HP harus berupa angka',
            'min_length' => 'No HP minimal 10 digit',
            'max_length' => 'No HP maksimal 15 digit'
        ],
        'tanggal_lahir' => [
            'valid_date' => 'Format tanggal lahir tidak valid'
        ],
        'alamat' => [
            'min_length' => 'Alamat minimal 5 karakter'
        ]
    ];

    protected $skipValidation = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function getWithUser($id = null)
    {
        $builder = $this->db->table('pelanggan');
        $builder->select('pelanggan.*, users.username, users.email');
        $builder->join('users', 'users.id = pelanggan.user_id');

        if ($id !== null) {
            $builder->where('pelanggan.id', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }

    public function generateId()
    {
        $lastId = $this->select('idpelanggan')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastId) {
            $number = (int) substr($lastId['idpelanggan'], 3) + 1;
        } else {
            $number = 1;
        }

        return 'PLG' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function save($data): bool
    {
        // Jika ini adalah update, modifikasi aturan is_unique
        if (!empty($data['id'])) {
            $this->validationRules['idpelanggan']['rules'] = 'required|max_length[10]|is_unique[pelanggan.idpelanggan,id,' . $data['id'] . ']';
            $this->validationRules['user_id']['rules'] = 'required|is_unique[pelanggan.user_id,id,' . $data['id'] . ']';
        }

        return parent::save($data);
    }

    public function getPelangganWithUser($id)
    {
        return $this->select('pelanggan.*, users.username, users.email')
            ->join('users', 'users.id = pelanggan.user_id', 'left')
            ->where('pelanggan.id', $id)
            ->first();
    }

    public function getPelangganDatatable($start, $length, $search)
    {
        // Konversi ke integer
        $start = (int) $start;
        $length = (int) $length;

        $builder = $this->select('pelanggan.*, users.username, users.email')
            ->join('users', 'users.id = pelanggan.user_id', 'left');

        // Total data
        $total = $builder->countAllResults(false);

        // Filter pencarian
        if ($search) {
            $builder->groupStart()
                ->like('pelanggan.idpelanggan', $search)
                ->orLike('pelanggan.nama_lengkap', $search)
                ->orLike('users.username', $search)
                ->orLike('users.email', $search)
                ->orLike('pelanggan.no_hp', $search)
                ->groupEnd();
        }

        // Total data setelah filter
        $filtered = $builder->countAllResults(false);

        // Get data dengan limit
        $data = $builder->orderBy('pelanggan.id', 'DESC')
            ->limit($length, $start)
            ->get()
            ->getResultArray();

        return [
            'total' => $total,
            'filtered' => $filtered,
            'data' => $data
        ];
    }
}
