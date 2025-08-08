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


        $builder = $this->db->table('pelanggan p')
            ->select('p.*, u.username, u.email')
            ->join('users u', 'u.id = p.user_id', 'left');


        $totalRecords = $builder->countAllResults(false);


        if (!empty($search)) {
            $searchValue = $this->db->escapeLikeString($search);

            $builder->groupStart()
                ->orLike('p.idpelanggan', $searchValue, 'both', null, true)
                ->orLike('p.nama_lengkap', $searchValue, 'both', null, true)
                ->orLike('p.jeniskelamin', $searchValue, 'both', null, true)
                ->orLike('p.no_hp', $searchValue, 'both', null, true)
                ->orLike('p.alamat', $searchValue, 'both', null, true)
                ->orLike('u.username', $searchValue, 'both', null, true)
                ->orLike('u.email', $searchValue, 'both', null, true)
                ->groupEnd();
        }


        $totalFiltered = $builder->countAllResults(false);


        $columns = ['p.idpelanggan', 'p.nama_lengkap', 'p.jeniskelamin', 'p.no_hp', 'p.alamat', 'u.username', 'u.email'];
        $orderColumn = isset($order[0]['column']) ? (int) $order[0]['column'] : 1;
        $orderDir = isset($order[0]['dir']) ? strtoupper($order[0]['dir']) : 'ASC';
        $orderField = $columns[$orderColumn - 1] ?? 'p.idpelanggan';


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

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $this->db->transBegin();

        try {
            $userId = null;
            $createUser = $this->request->getPost('createUser');


            if ($createUser === 'on' || $createUser === '1' || $createUser === 'true') {
                $userData = [
                    'email' => $this->request->getPost('email'),
                    'username' => $this->request->getPost('username'),
                    'password' => $this->request->getPost('password'),
                    'role' => 'pelanggan',
                    'status' => 'active',
                    'name' => $this->request->getPost('nama_lengkap')
                ];

                if (!$this->userModel->insert($userData)) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'errors' => $this->userModel->errors()
                    ]);
                }

                $userId = $this->userModel->getInsertID();
            }


            $pelangganData = [
                'idpelanggan' => $this->request->getPost('idpelanggan'),
                'user_id' => $userId,
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'jeniskelamin' => $this->request->getPost('jeniskelamin'),
                'no_hp' => $this->request->getPost('no_hp'),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
                'alamat' => $this->request->getPost('alamat')
            ];

            if (!$this->pelangganModel->insert($pelangganData)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $this->pelangganModel->errors()
                ]);
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data pelanggan berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data'
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

        $this->db->transBegin();

        try {
            $userId = $pelanggan['user_id'];
            $createUser = $this->request->getPost('createUser');


            if (($createUser === 'on' || $createUser === '1' || $createUser === 'true') && empty($pelanggan['user_id'])) {

                $userData = [
                    'email' => $this->request->getPost('email'),
                    'username' => $this->request->getPost('username'),
                    'password' => $this->request->getPost('password'),
                    'role' => 'pelanggan',
                    'status' => 'active',
                    'name' => $this->request->getPost('nama_lengkap')
                ];

                if (!$this->userModel->insert($userData)) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'errors' => $this->userModel->errors()
                    ]);
                }

                $userId = $this->userModel->getInsertID();
            } elseif (!empty($pelanggan['user_id'])) {

                $updateData = ['name' => $this->request->getPost('nama_lengkap')];


                if ($this->request->getPost('password')) {
                    $updateData['password'] = $this->request->getPost('password');
                }

                if (!$this->userModel->update($pelanggan['user_id'], $updateData)) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'errors' => $this->userModel->errors()
                    ]);
                }
            }


            $pelangganData = [
                'user_id' => $userId,
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'jeniskelamin' => $this->request->getPost('jeniskelamin'),
                'no_hp' => $this->request->getPost('no_hp'),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
                'alamat' => $this->request->getPost('alamat')
            ];

            if (!$this->pelangganModel->update($id, $pelangganData)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $this->pelangganModel->errors()
                ]);
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data pelanggan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data'
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

            if (!$this->pelangganModel->delete($id)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menghapus data pelanggan'
                ]);
            }


            if (!empty($pelanggan['user_id'])) {
                if (!$this->userModel->delete($pelanggan['user_id'])) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal menghapus data user'
                    ]);
                }
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data pelanggan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data'
            ]);
        }
    }


    public function profile()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'pelanggan') {
            return redirect()->to('customer/login');
        }

        $userId = session()->get('user_id');


        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('customer/login')->with('error', 'Data user tidak ditemukan');
        }


        $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();


        if (!$pelanggan) {
            $pelanggan = [
                'idpelanggan' => $this->pelangganModel->generateId(),
                'nama_lengkap' => $user['name'],
                'jeniskelamin' => '',
                'alamat' => '',
                'no_hp' => '',
                'tanggal_lahir' => '',
                'created_at' => $user['created_at'],
                'email' => $user['email'],
                'username' => $user['username']
            ];
        } else {

            $pelanggan['email'] = $user['email'];
            $pelanggan['username'] = $user['username'];
        }

        return view('pelanggan/profile', ['pelanggan' => $pelanggan]);
    }


    public function updateProfile()
    {

        if ($this->request->isAJAX() && !$this->validateCSRFToken()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Token keamanan tidak valid. Silakan refresh halaman.'
            ]);
        }


        if (!session()->get('logged_in') || session()->get('role') !== 'pelanggan') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Anda harus login terlebih dahulu'
                ]);
            }
            return redirect()->to('customer/login');
        }

        $userId = session()->get('user_id');


        $user = $this->userModel->find($userId);

        if (!$user) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data user tidak ditemukan'
                ]);
            }
            return redirect()->to('customer/login')->with('error', 'Data user tidak ditemukan');
        }


        $rules = [
            'nama_lengkap' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama lengkap harus diisi',
                    'min_length' => 'Nama lengkap minimal 3 karakter'
                ]
            ],
            'no_hp' => [
                'rules' => 'permit_empty|numeric|min_length[10]',
                'errors' => [
                    'numeric' => 'Nomor HP hanya boleh berisi angka',
                    'min_length' => 'Nomor HP minimal 10 digit'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }


        $userData = [
            'name' => $this->request->getPost('nama_lengkap')
        ];

        $pelangganData = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'jeniskelamin' => $this->request->getPost('jeniskelamin'),
            'no_hp' => $this->request->getPost('no_hp'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'alamat' => $this->request->getPost('alamat'),
            'user_id' => $userId
        ];

        $this->db->transBegin();

        try {

            if (!$this->userModel->update($userId, $userData)) {
                throw new \Exception('Gagal memperbarui data user');
            }


            $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();


            $this->pelangganModel->skipValidation(true);

            if (!$pelanggan) {

                $pelangganData['idpelanggan'] = $this->pelangganModel->generateId();
                if (!$this->pelangganModel->insert($pelangganData)) {
                    throw new \Exception('Gagal menyimpan data pelanggan: ' . json_encode($this->pelangganModel->errors()));
                }
            } else {

                if (!$this->pelangganModel->update($pelanggan['id'], $pelangganData)) {
                    throw new \Exception('Gagal memperbarui data pelanggan: ' . json_encode($this->pelangganModel->errors()));
                }
            }

            $this->db->transCommit();

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Profil berhasil diperbarui'
                ]);
            }
            return redirect()->to('customer/profil')->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            $this->db->transRollback();

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage());
        }
    }


    private function validateCSRFToken()
    {
        $csrf = csrf_hash();
        $csrfName = csrf_token();

        if (empty($_POST[$csrfName]) || $_POST[$csrfName] !== $csrf) {
            return false;
        }

        return true;
    }
}
