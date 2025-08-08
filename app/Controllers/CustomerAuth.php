<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OtpModel;
use App\Models\PelangganModel;


class CustomerAuth extends BaseController
{
    protected $userModel;
    protected $otpModel;
    protected $pelangganModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->otpModel = new OtpModel();
        $this->pelangganModel = new PelangganModel();
        helper(['cookie', 'email']);
    }

    public function index()
    {

        if (session()->get('logged_in')) {
            return redirect()->to(site_url());
        }

        return view('auth/customer/login');
    }

    public function register()
    {
        return view('auth/customer/register');
    }

    public function doRegister()
    {
        $email = $this->request->getPost('email');
        $username = $this->request->getPost('username');


        $existingUser = $this->userModel->where('email', $email)->first();

        if ($existingUser) {
            if ($existingUser['status'] === 'inactive') {

                $otp = $this->otpModel->generateOTP($existingUser['id']);
                send_otp_email($email, $otp);


                session()->set('temp_user_id', $existingUser['id']);

                return $this->response->setJSON([
                    'status' => 'pending_verification',
                    'message' => 'Email ini sudah terdaftar tapi belum diverifikasi. Kami telah mengirim kode OTP baru ke email Anda.',
                    'redirect' => site_url('customer/verify')
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau reset password jika Anda lupa.'
                ]);
            }
        }


        $existingUsername = $this->userModel->where('username', $username)->first();
        if ($existingUsername) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Username ini sudah digunakan. Silakan pilih username lain.'
            ]);
        }

        $rules = [
            'username' => [
                'rules' => 'required|alpha_numeric_space|min_length[3]',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'alpha_numeric_space' => 'Username hanya boleh berisi huruf, angka, dan spasi',
                    'min_length' => 'Username minimal 3 karakter'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Format email tidak valid'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ],
            'name' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama lengkap harus diisi',
                    'min_length' => 'Nama lengkap minimal 3 karakter'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);
        }

        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $this->request->getPost('password'),
            'name' => $this->request->getPost('name'),
            'role' => 'pelanggan',
            'status' => 'inactive'
        ];

        $this->userModel->insert($userData);
        $userId = $this->userModel->getInsertID();


        $otp = $this->otpModel->generateOTP($userId);
        send_otp_email($userData['email'], $otp);


        session()->set('temp_user_id', $userId);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Registrasi berhasil! Silakan cek email Anda untuk kode OTP.',
            'redirect' => site_url('customer/verify')
        ]);
    }

    public function verify()
    {
        if (!session()->get('temp_user_id')) {
            return redirect()->to('customer/register');
        }
        return view('auth/customer/verify');
    }

    public function doVerify()
    {
        $userId = session()->get('temp_user_id');
        $otp = $this->request->getPost('otp');

        if (!$userId || !$otp) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data verifikasi tidak valid'
            ]);
        }

        if ($this->otpModel->verifyOTP($userId, $otp)) {
            try {

                $this->userModel->update($userId, ['status' => 'active']);


                $user = $this->userModel->find($userId);


                $idpelanggan = $this->pelangganModel->generateId();


                $pelangganData = [
                    'idpelanggan' => $idpelanggan,
                    'user_id' => $userId,
                    'nama_lengkap' => $user['name'],
                    'jeniskelamin' => '-',
                    'alamat' => '-',
                    'no_hp' => '', // Minimal 10 digit sesuai validasi
                    'tanggal_lahir' => null
                ];


                $this->pelangganModel->skipValidation(true);

                if (!$this->pelangganModel->insert($pelangganData)) {
                    throw new \Exception('Gagal membuat data pelanggan');
                }


                session()->remove('temp_user_id');

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Verifikasi berhasil! Silakan login dan lengkapi profil Anda.',
                    'redirect' => site_url('customer/login')
                ]);
            } catch (\Exception $e) {
                log_message('error', '[CustomerAuth::doVerify] Error: ' . $e->getMessage());
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat memproses data. Silakan coba lagi.'
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Kode OTP tidak valid atau sudah kadaluarsa'
        ]);
    }

    public function resendOTP()
    {
        $userId = session()->get('temp_user_id');
        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sesi tidak valid'
            ]);
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User tidak ditemukan'
            ]);
        }


        $otp = $this->otpModel->generateOTP($userId);
        send_otp_email($user['email'], $otp);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Kode OTP baru telah dikirim ke email Anda'
        ]);
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember') == 'on';

        $user = $this->userModel->where('username', $username)
            ->orWhere('email', $username)
            ->where('role', 'pelanggan')
            ->first();

        if ($user) {
            if ($user['status'] !== 'active') {

                session()->set('temp_user_id', $user['id']);
                return $this->response->setJSON([
                    'status' => 'pending_verification',
                    'message' => 'Akun Anda belum diverifikasi. Silakan verifikasi email Anda.',
                    'redirect' => site_url('customer/verify')
                ]);
            }

            if (password_verify($password, $user['password'])) {

                $this->userModel->update($user['id'], [
                    'last_login' => date('Y-m-d H:i:s')
                ]);


                $sessionData = [
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'logged_in' => true
                ];
                session()->set($sessionData);

                if ($remember) {
                    $this->setRememberMeCookie($user['id']);
                }


                $pelanggan = $this->pelangganModel->where('user_id', $user['id'])->first();
                $redirect = $pelanggan ? site_url() : site_url('customer/complete-profile');

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'redirect' => $redirect
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Username/Email atau Password salah'
        ]);
    }

    public function logout()
    {

        if (get_cookie('remember_token')) {
            delete_cookie('remember_token');
            delete_cookie('user_id');
        }


        session()->destroy();

        return redirect()->to('customer/login')->with('message', 'Anda telah berhasil logout');
    }

    protected function setRememberMeCookie($userId)
    {
        $token = bin2hex(random_bytes(32));


        $this->userModel->update($userId, [
            'remember_token' => $token
        ]);


        $expires = 30 * 24 * 60 * 60; // 30 hari
        $secure = isset($_SERVER['HTTPS']); // Set secure hanya jika HTTPS


        set_cookie(
            'remember_token',
            $token,
            $expires,
            '',  // domain
            '/', // path
            '', // prefix
            $secure, // secure - hanya set true jika HTTPS
            true  // httponly
        );


        set_cookie(
            'user_id',
            $userId,
            $expires,
            '',
            '/',
            '',
            $secure,
            true
        );
    }
}
