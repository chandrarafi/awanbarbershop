<?php

namespace App\Controllers;

use App\Models\PaketModel;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;

class Paket extends ResourceController
{
    protected $paketModel;
    protected $db;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
        $this->db = Database::connect();
    }

    public function index()
    {
        $title = 'Manajemen Paket';
        return view('admin/paket/index', compact('title'));
    }

    public function getPaket()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Invalid request'
            ]);
        }

        $request = $this->request;

        $start = (int) $request->getGet('start');
        $length = (int) $request->getGet('length');
        $search = trim($request->getGet('search')['value'] ?? '');
        $order = $request->getGet('order') ?? [];

        // Query dasar dengan index hints untuk optimasi
        $builder = $this->db->table('paket USE INDEX (PRIMARY)');

        // Total records (cache result)
        $totalRecords = $this->db->table('paket')->countAllResults();

        // Pencarian yang dioptimalkan
        if (!empty($search)) {
            $searchValue = $this->db->escapeLikeString($search);

            $builder->groupStart();

            // Gunakan OR LIKE untuk pencarian lebih cepat
            $builder->orLike('idpaket', $searchValue, 'both', null, true)
                ->orLike('namapaket', $searchValue, 'both', null, true)
                ->orLike('deskripsi', $searchValue, 'both', null, true);

            // Jika input adalah angka atau format rupiah
            if (preg_match('/^[Rp\s.,]*(\d+)/', $search, $matches)) {
                $numericSearch = $matches[1];
                $builder->orWhere('harga', $numericSearch);
            }

            $builder->groupEnd();
        }

        // Hitung total filtered records
        $totalFiltered = $builder->countAllResults(false);

        // Pengurutan yang dioptimalkan
        $columns = ['idpaket', 'namapaket', 'deskripsi', 'harga'];
        $orderColumn = isset($order[0]['column']) ? (int) $order[0]['column'] : 1;
        $orderDir = isset($order[0]['dir']) ? strtoupper($order[0]['dir']) : 'ASC';
        $orderField = $columns[$orderColumn - 1] ?? 'idpaket';

        // Ambil data dengan limit
        $results = $builder->orderBy($orderField, $orderDir)
            ->limit($length, $start)
            ->get()
            ->getResultArray();

        // Format data
        $data = array_map(function ($row) {
            return [
                'idpaket' => $row['idpaket'],
                'namapaket' => $row['namapaket'],
                'deskripsi' => $row['deskripsi'],
                'harga' => $row['harga'],
                'harga_formatted' => 'Rp ' . number_format($row['harga'], 0, ',', '.')
            ];
        }, $results);

        return $this->response->setJSON([
            'draw' => (int) $request->getGet('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    public function getNewId()
    {
        $lastId = $this->paketModel->select('idpaket')
            ->orderBy('idpaket', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();

        if (!$lastId) {
            $newId = 'PKT001';
        } else {
            $lastNumber = (int) substr($lastId->idpaket, 3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            $newId = 'PKT' . $newNumber;
        }

        return $this->response->setJSON(['idpaket' => $newId]);
    }

    public function getById($id = null)
    {
        $paket = $this->paketModel->find($id);

        if (!$paket) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Paket tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $paket
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        if ($this->paketModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Paket berhasil ditambahkan'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menambahkan paket',
            'errors' => $this->paketModel->errors()
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();

        if (empty($id)) {
            $id = $data['idpaket'];
        } else {
            $data['idpaket'] = $id;
        }

        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'ID Paket tidak valid'
            ]);
        }

        $existingPaket = $this->paketModel->find($id);
        if (!$existingPaket) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Paket tidak ditemukan'
            ]);
        }

        if ($this->paketModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Paket berhasil diperbarui'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal memperbarui paket',
            'errors' => $this->paketModel->errors()
        ]);
    }

    public function delete($id = null)
    {
        if ($this->paketModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Paket berhasil dihapus'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus paket'
        ]);
    }
}
