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


        $builder = $this->db->table('paket USE INDEX (PRIMARY)');


        $totalRecords = $this->db->table('paket')->countAllResults();


        if (!empty($search)) {
            $searchValue = $this->db->escapeLikeString($search);

            $builder->groupStart();


            $builder->orLike('idpaket', $searchValue, 'both', null, true)
                ->orLike('namapaket', $searchValue, 'both', null, true)
                ->orLike('deskripsi', $searchValue, 'both', null, true);


            if (preg_match('/^[Rp\s.,]*(\d+)/', $search, $matches)) {
                $numericSearch = $matches[1];
                $builder->orWhere('harga', $numericSearch);
            }

            $builder->groupEnd();
        }


        $totalFiltered = $builder->countAllResults(false);


        $columns = ['idpaket', 'namapaket', 'deskripsi', 'harga'];
        $orderColumn = isset($order[0]['column']) ? (int) $order[0]['column'] : 1;
        $orderDir = isset($order[0]['dir']) ? strtoupper($order[0]['dir']) : 'ASC';
        $orderField = $columns[$orderColumn - 1] ?? 'idpaket';


        $results = $builder->orderBy($orderField, $orderDir)
            ->limit($length, $start)
            ->get()
            ->getResultArray();


        $data = array_map(function ($row) {
            return [
                'idpaket' => $row['idpaket'],
                'namapaket' => $row['namapaket'],
                'deskripsi' => $row['deskripsi'],
                'harga' => $row['harga'],
                'harga_formatted' => 'Rp ' . number_format($row['harga'], 0, ',', '.'),
                'gambar' => $row['image']
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

    public function create()
    {
        $title = 'Tambah Paket';
        return view('admin/paket/create', compact('title'));
    }

    public function edit($id = null)
    {
        if (empty($id)) {
            return redirect()->to('admin/paket')->with('error', 'ID Paket tidak valid');
        }

        $paket = $this->paketModel->find($id);
        if (!$paket) {
            return redirect()->to('admin/paket')->with('error', 'Paket tidak ditemukan');
        }

        $title = 'Edit Paket';
        $idpaket = $id;
        return view('admin/paket/edit', compact('title', 'idpaket'));
    }

    public function store()
    {
        $data = $this->request->getPost();
        $image = $this->request->getFile('gambar');

        if ($this->paketModel->save($data)) {
            $insertId = $this->paketModel->getInsertID();

            if ($image && $image->isValid()) {
                $uploadResult = $this->paketModel->uploadImage($image);

                if (!$uploadResult['status']) {
                    $this->paketModel->delete($insertId);

                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => $uploadResult['error']
                    ]);
                }

                $this->paketModel->update($insertId, ['image' => $uploadResult['filename']]);
            }

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


        $image = $this->request->getFile('gambar');

        if ($image && $image->isValid()) {

            $uploadResult = $this->paketModel->uploadImage($image);

            if (!$uploadResult['status']) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => $uploadResult['error']
                ]);
            }


            if (!empty($existingPaket['image'])) {
                $this->paketModel->deleteImage($existingPaket['image']);
            }

            $data['image'] = $uploadResult['filename'];
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
        $paket = $this->paketModel->find($id);

        if ($paket) {

            if (!empty($paket['image'])) {
                $this->paketModel->deleteImage($paket['image']);
            }

            if ($this->paketModel->delete($id)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Paket berhasil dihapus'
                ]);
            }
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus paket'
        ]);
    }
}
