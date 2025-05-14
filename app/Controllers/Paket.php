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
        $request = $this->request;

        $start = $request->getGet('start') ?? 0;
        $length = $request->getGet('length') ?? 10;
        $search = $request->getGet('search')['value'] ?? '';
        $order = $request->getGet('order') ?? [];

        $orderColumn = $order[0]['column'] ?? 0;
        $orderDir = $order[0]['dir'] ?? 'asc';

        $columns = ['idpaket', 'namapaket', 'deskripsi', 'harga'];
        $orderBy = $columns[$orderColumn] ?? 'idpaket';

        $builder = $this->paketModel->builder();

        if (!empty($search)) {
            $builder->groupStart()
                ->like('idpaket', $search)
                ->orLike('namapaket', $search)
                ->orLike('deskripsi', $search)
                ->orLike('harga', $search)
                ->groupEnd();
        }

        $totalRecords = $this->paketModel->countAll();
        $filteredRecords = $builder->countAllResults(false);

        $data = $builder->orderBy($orderBy, $orderDir)
            ->limit($length, $start)
            ->get()
            ->getResultArray();

        $response = [
            'draw' => $request->getGet('draw') ?? 1,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ];

        return $this->response->setJSON($response);
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
