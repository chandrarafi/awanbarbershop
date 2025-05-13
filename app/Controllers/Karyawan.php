<?php

namespace App\Controllers;

use App\Models\KaryawanModel;

class Karyawan extends BaseController
{
    protected $karyawanModel;

    public function __construct()
    {
        $this->karyawanModel = new KaryawanModel();
    }

    public function index()
    {
        $title = 'Manajemen Karyawan';
        return view('admin/karyawan/index', compact('title'));
    }

    public function getKaryawan()
    {
        $request = $this->request;

        $start = $request->getGet('start') ?? 0;
        $length = $request->getGet('length') ?? 10;
        $search = $request->getGet('search')['value'] ?? '';
        $order = $request->getGet('order') ?? [];

        $orderColumn = $order[0]['column'] ?? 0;
        $orderDir = $order[0]['dir'] ?? 'asc';

        $columns = ['idkaryawan', 'namakaryawan', 'alamat', 'nohp'];
        $orderBy = $columns[$orderColumn] ?? 'idkaryawan';

        $builder = $this->karyawanModel->builder();

        if (!empty($search)) {
            $builder->groupStart()
                ->like('idkaryawan', $search)
                ->orLike('namakaryawan', $search)
                ->orLike('alamat', $search)
                ->orLike('nohp', $search)
                ->groupEnd();
        }

        $totalRecords = $this->karyawanModel->countAll();
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
