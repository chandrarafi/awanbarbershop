<?php

namespace App\Controllers;

use App\Models\KaryawanModel;

class Karyawan extends BaseController
{
    protected $karyawanModel;
    protected $db;

    public function __construct()
    {
        $this->karyawanModel = new KaryawanModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $title = 'Manajemen Karyawan';
        return view('admin/karyawan/index', compact('title'));
    }

    public function getKaryawan()
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


        $builder = $this->db->table('karyawan USE INDEX (PRIMARY)');


        $totalRecords = $this->db->table('karyawan')->countAllResults();


        if (!empty($search)) {
            $searchValue = $this->db->escapeLikeString($search);

            $builder->groupStart()
                ->orLike('idkaryawan', $searchValue, 'both', null, true)
                ->orLike('namakaryawan', $searchValue, 'both', null, true)
                ->orLike('alamat', $searchValue, 'both', null, true)
                ->orLike('nohp', $searchValue, 'both', null, true)
                ->groupEnd();
        }


        $totalFiltered = $builder->countAllResults(false);


        $columns = ['idkaryawan', 'namakaryawan', 'alamat', 'nohp'];
        $orderColumn = isset($order[0]['column']) ? (int) $order[0]['column'] : 1;
        $orderDir = isset($order[0]['dir']) ? strtoupper($order[0]['dir']) : 'ASC';
        $orderField = $columns[$orderColumn - 1] ?? 'idkaryawan';


        $results = $builder->orderBy($orderField, $orderDir)
            ->limit($length, $start)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'draw' => (int) $request->getGet('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $results
        ]);
    }

    public function getNewId()
    {
        $newId = $this->karyawanModel->generateId();
        return $this->response->setJSON(['idkaryawan' => $newId]);
    }

    public function getById($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);

        if (!$karyawan) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Karyawan tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $karyawan
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        if ($this->karyawanModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Karyawan berhasil ditambahkan'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menambahkan karyawan',
            'errors' => $this->karyawanModel->errors()
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();

        if (empty($id)) {
            $id = $data['idkaryawan'];
        } else {
            $data['idkaryawan'] = $id;
        }

        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'ID Karyawan tidak valid'
            ]);
        }

        $existingKaryawan = $this->karyawanModel->find($id);
        if (!$existingKaryawan) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Karyawan tidak ditemukan'
            ]);
        }

        if ($this->karyawanModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Karyawan berhasil diperbarui'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal memperbarui karyawan',
            'errors' => $this->karyawanModel->errors()
        ]);
    }

    public function delete($id = null)
    {
        if ($this->karyawanModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Karyawan berhasil dihapus'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus karyawan'
        ]);
    }
}
