<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $title = 'Dashboard';
        return view('admin/dashboard', compact('title'));
    }


    public function users()
    {
        $title = 'User Management';
        return view('admin/users/index', compact('title'));
    }

    public function getUsers()
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


        $builder = $this->db->table('users USE INDEX (PRIMARY)');


        $totalRecords = $this->db->table('users')->countAllResults();


        if (!empty($search)) {
            $searchValue = $this->db->escapeLikeString($search);

            $builder->groupStart()
                ->orLike('username', $searchValue, 'both', null, true)
                ->orLike('email', $searchValue, 'both', null, true)
                ->orLike('name', $searchValue, 'both', null, true)
                ->orLike('role', $searchValue, 'both', null, true)
                ->groupEnd();
        }


        $totalFiltered = $builder->countAllResults(false);


        $columns = ['id', 'username', 'email', 'name', 'role', 'status', 'last_login'];
        $orderColumn = isset($order[0]['column']) ? (int) $order[0]['column'] : 1;
        $orderDir = isset($order[0]['dir']) ? strtoupper($order[0]['dir']) : 'ASC';
        $orderField = $columns[$orderColumn - 1] ?? 'id';


        $results = $builder->orderBy($orderField, $orderDir)
            ->limit($length, $start)
            ->get()
            ->getResultArray();


        $data = array_map(function ($row) {
            return [
                'id' => $row['id'],
                'username' => $row['username'],
                'email' => $row['email'],
                'name' => $row['name'],
                'role' => $row['role'],
                'status' => $row['status'],
                'last_login' => $row['last_login'] ? date('d/m/Y H:i', strtotime($row['last_login'])) : '-'
            ];
        }, $results);

        return $this->response->setJSON([
            'draw' => (int) $request->getGet('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    protected function handleUserSave($data, $isNew = true)
    {
        if ($this->userModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => $isNew ? 'User berhasil ditambahkan' : 'User berhasil diperbarui'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => $isNew ? 'Gagal menambahkan user' : 'Gagal memperbarui user',
            'errors' => $this->userModel->errors()
        ]);
    }

    public function addUser()
    {
        return $this->handleUserSave($this->request->getPost(), true);
    }

    public function createUser()
    {
        return $this->handleUserSave($this->request->getJSON(true), true);
    }

    public function getUser($id = null)
    {
        $data = $this->userModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'User tidak ditemukan'
        ]);
    }

    public function updateUser($id = null)
    {
        $data = $this->request->getPost();


        if (!empty($id)) {
            $data['id'] = $id;
        } elseif (!empty($data['id'])) {
            $id = $data['id'];
        }


        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'ID user tidak valid',
                'errors' => ['id' => 'ID user tidak ditemukan']
            ]);
        }


        $existingUser = $this->userModel->find($id);
        if (!$existingUser) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'User tidak ditemukan',
                'errors' => ['id' => 'User dengan ID tersebut tidak ditemukan']
            ]);
        }

        return $this->handleUserSave($data, false);
    }

    public function deleteUser($id = null)
    {
        if ($this->userModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'User berhasil dihapus'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 'error',
            'message' => 'Gagal menghapus user'
        ]);
    }

    public function getRoles()
    {

        $roles = ['admin', 'pimpinan', 'user'];

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $roles
        ]);
    }
}
