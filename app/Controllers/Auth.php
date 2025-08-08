<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OtpModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $otpModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->otpModel = new OtpModel();

        helper(['cookie', 'email']);
    }

    public function index()
    {

        if (session()->get('logged_in')) {
            return redirect()->to(session()->get('redirect_url') ?? 'admin');
        }

        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function doRegister()
    {
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'name' => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'name' => $this->request->getPost('name'),
            'role' => 'pelanggan',
            'status' => 'inactive' // Status inactive sampai verifikasi OTP
        ];

        $this->userModel->insert($userData);
        $userId = $this->userModel->getInsertID();


        $otp = $this->otpModel->generateOTP($userId);
        send_otp_email($userData['email'], $otp);


        session()->set('temp_user_id', $userId);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Registrasi berhasil! Silakan cek email Anda untuk kode OTP.',
            'redirect' => site_url('auth/verify')
        ]);
    }

    public function verify()
    {
        if (!session()->get('temp_user_id')) {
            return redirect()->to('auth/register');
        }
        return view('auth/verify');
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

            $this->userModel->update($userId, ['status' => 'active']);


            session()->remove('temp_user_id');

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Verifikasi berhasil! Silakan login.',
                'redirect' => site_url('auth')
            ]);
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
            ->first();

        if ($user) {
            if ($user['status'] !== 'active') {
                if ($user['role'] === 'pelanggan') {

                    session()->set('temp_user_id', $user['id']);
                    return $this->response->setJSON([
                        'status' => 'pending_verification',
                        'message' => 'Akun Anda belum diverifikasi. Silakan verifikasi email Anda.',
                        'redirect' => site_url('auth/verify')
                    ]);
                }
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
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

                $redirect = $user['role'] === 'pelanggan' ? site_url() : site_url('admin');
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

        return redirect()->to('auth')->with('message', 'Anda telah berhasil logout');
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
