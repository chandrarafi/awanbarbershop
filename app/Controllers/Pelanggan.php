<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Pelanggan extends BaseController
{
    protected $pelangganModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $title = 'Manajemen Pelanggan';
        return view('admin/pelanggan/index', compact('title'));
    }

    public function create()
    {
        $title = 'Tambah Pelanggan';
        return view('admin/pelanggan/create', compact('title'));
    }

    public function edit($id)
    {
        $title = 'Edit Pelanggan';
        $pelanggan = $this->pelangganModel->getPelangganWithUser($id);

        if (!$pelanggan) {
            return redirect()->to('admin/pelanggan')->with('error', 'Pelanggan tidak ditemukan');
        }

        return view('admin/pelanggan/edit', compact('title', 'pelanggan'));
    }

    public function getNewId()
    {
        $idpelanggan = $this->pelangganModel->generateId();
        return $this->response->setJSON(['idpelanggan' => $idpelanggan]);
    }

    public function getPelanggan()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $start = $this->request->getVar('start');
        $length = $this->request->getVar('length');
        $search = $this->request->getVar('search')['value'] ?? '';

        $result = $this->pelangganModel->getPelangganDatatable($start, $length, $search);

        return $this->response->setJSON([
            'draw' => $this->request->getVar('draw'),
            'recordsTotal' => $result['total'],
            'recordsFiltered' => $result['filtered'],
            'data' => $result['data']
        ]);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        // Validasi ID Pelanggan terlebih dahulu
        if (!$this->request->getPost('idpelanggan')) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => ['idpelanggan' => 'ID Pelanggan harus diisi']
            ]);
        }

        $createUser = $this->request->getPost('createUser');
        $rules = [
            'idpelanggan' => 'required|is_unique[pelanggan.idpelanggan]',
            'nama_lengkap' => 'required|min_length[3]',
            'jeniskelamin' => 'permit_empty|in_list[Laki-laki,Perempuan]',
            'no_hp' => 'permit_empty|numeric|min_length[10]|max_length[15]',
            'tanggal_lahir' => 'permit_empty|valid_date',
            'alamat' => 'permit_empty|min_length[5]'
        ];

        // Jika createUser dicentang, tambahkan validasi untuk data user
        if ($createUser === 'on' || $createUser === '1' || $createUser === 'true') {
            $rules['email'] = 'required|valid_email|is_unique[users.email]';
            $rules['username'] = 'required|alpha_numeric|min_length[3]|is_unique[users.username]';
            $rules['password'] = 'required|min_length[6]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $this->db->transBegin();

        try {
            $userId = null;

            // Jika createUser dicentang, buat akun user terlebih dahulu
            if ($createUser === 'on' || $createUser === '1' || $createUser === 'true') {
                log_message('info', 'Mencoba membuat user baru dengan data: ' . json_encode([
                    'email' => $this->request->getPost('email'),
                    'username' => $this->request->getPost('username')
                ]));

                $userData = [
                    'email' => $this->request->getPost('email'),
                    'username' => $this->request->getPost('username'),
                    'password' => $this->request->getPost('password'),
                    'role' => 'pelanggan',
                    'status' => 'active',
                    'name' => $this->request->getPost('nama_lengkap')
                ];

                if (!$this->userModel->insert($userData)) {
                    throw new \Exception('Gagal menyimpan data user: ' . print_r($this->userModel->errors(), true));
                }

                $userId = $this->userModel->getInsertID();
                log_message('info', 'User berhasil dibuat dengan ID: ' . $userId);
            }

            // Data pelanggan
            $pelangganData = [
                'idpelanggan' => $this->request->getPost('idpelanggan'),
                'user_id' => $userId,
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'jeniskelamin' => $this->request->getPost('jeniskelamin'),
                'no_hp' => $this->request->getPost('no_hp'),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
                'alamat' => $this->request->getPost('alamat')
            ];

            log_message('info', 'Mencoba menyimpan data pelanggan: ' . json_encode($pelangganData));

            if (!$this->pelangganModel->insert($pelangganData)) {
                throw new \Exception('Gagal menyimpan data pelanggan: ' . print_r($this->pelangganModel->errors(), true));
            }

            $insertedId = $this->pelangganModel->getInsertID();
            log_message('info', 'Pelanggan berhasil disimpan dengan ID: ' . $insertedId);

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data pelanggan berhasil disimpan',
                'id' => $insertedId
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error saat menyimpan pelanggan: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ]);
        }
    }

    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $pelanggan = $this->pelangganModel->find($id);
        if (!$pelanggan) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pelanggan tidak ditemukan'
            ]);
        }

        $createUser = $this->request->getPost('createUser');
        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'jeniskelamin' => 'permit_empty|in_list[Laki-laki,Perempuan]',
            'no_hp' => 'permit_empty|numeric|min_length[10]|max_length[15]',
            'tanggal_lahir' => 'permit_empty|valid_date',
            'alamat' => 'permit_empty|min_length[5]'
        ];

        // Jika createUser dicentang dan belum punya user_id, tambahkan validasi
        if (($createUser === 'on' || $createUser === '1' || $createUser === 'true') && empty($pelanggan['user_id'])) {
            $rules['email'] = 'required|valid_email|is_unique[users.email]';
            $rules['username'] = 'required|alpha_numeric|min_length[3]|is_unique[users.username]';
            $rules['password'] = 'required|min_length[6]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $this->db->transBegin();

        try {
            $userId = $pelanggan['user_id'];

            // Update atau buat user jika diperlukan
            if (($createUser === 'on' || $createUser === '1' || $createUser === 'true') && empty($pelanggan['user_id'])) {
                log_message('info', 'Mencoba membuat user baru untuk pelanggan ID: ' . $id);

                $userData = [
                    'email' => $this->request->getPost('email'),
                    'username' => $this->request->getPost('username'),
                    'password' => $this->request->getPost('password'),
                    'role' => 'pelanggan',
                    'status' => 'active',
                    'name' => $this->request->getPost('nama_lengkap')
                ];

                if (!$this->userModel->insert($userData)) {
                    throw new \Exception('Gagal menyimpan data user: ' . print_r($this->userModel->errors(), true));
                }

                $userId = $this->userModel->getInsertID();
                log_message('info', 'User berhasil dibuat dengan ID: ' . $userId);
            } elseif (!empty($pelanggan['user_id']) && $this->request->getPost('password')) {
                // Update password dan nama jika diisi
                log_message('info', 'Mengupdate data user ID: ' . $pelanggan['user_id']);

                $updateData = [
                    'name' => $this->request->getPost('nama_lengkap')
                ];

                // Hanya update password jika diisi
                if ($this->request->getPost('password')) {
                    $updateData['password'] = $this->request->getPost('password');
                }

                if (!$this->userModel->update($pelanggan['user_id'], $updateData)) {
                    throw new \Exception('Gagal mengupdate data user: ' . print_r($this->userModel->errors(), true));
                }
            } elseif (!empty($pelanggan['user_id'])) {
                // Update nama saja jika user sudah ada
                if (!$this->userModel->update($pelanggan['user_id'], [
                    'name' => $this->request->getPost('nama_lengkap')
                ])) {
                    throw new \Exception('Gagal mengupdate nama user');
                }
            }

            // Update data pelanggan
            $pelangganData = [
                'user_id' => $userId,
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'jeniskelamin' => $this->request->getPost('jeniskelamin'),
                'no_hp' => $this->request->getPost('no_hp'),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
                'alamat' => $this->request->getPost('alamat')
            ];

            log_message('info', 'Mencoba mengupdate data pelanggan: ' . json_encode($pelangganData));

            if (!$this->pelangganModel->update($id, $pelangganData)) {
                throw new \Exception('Gagal mengupdate data pelanggan: ' . print_r($this->pelangganModel->errors(), true));
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data pelanggan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error saat mengupdate pelanggan: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $pelanggan = $this->pelangganModel->find($id);
        if (!$pelanggan) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pelanggan tidak ditemukan'
            ]);
        }

        $this->db->transBegin();

        try {
            // Hapus data pelanggan terlebih dahulu
            log_message('info', 'Mencoba menghapus data pelanggan ID: ' . $id);

            if (!$this->pelangganModel->delete($id)) {
                throw new \Exception('Gagal menghapus data pelanggan');
            }

            // Jika pelanggan memiliki user account, hapus juga user-nya
            if (!empty($pelanggan['user_id'])) {
                log_message('info', 'Mencoba menghapus data user ID: ' . $pelanggan['user_id']);

                if (!$this->userModel->delete($pelanggan['user_id'])) {
                    throw new \Exception('Gagal menghapus data user');
                }
            }

            $this->db->transCommit();
            log_message('info', 'Data pelanggan dan user berhasil dihapus');

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data pelanggan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error saat menghapus pelanggan: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ]);
        }
    }
}
