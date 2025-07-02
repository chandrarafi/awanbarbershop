<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Load helper cookie
        helper('cookie');

        if (!session()->get('logged_in')) {
            // Cek remember me cookie
            $userId = get_cookie('user_id');
            $rememberToken = get_cookie('remember_token');

            if ($userId && $rememberToken) {
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find($userId);

                if ($user && $user['remember_token'] === $rememberToken) {
                    // Set session
                    $sessionData = [
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'name' => $user['name'],
                        'role' => $user['role'],
                        'logged_in' => true
                    ];
                    session()->set($sessionData);

                    // Redirect pimpinan to reports page
                    if ($user['role'] === 'pimpinan') {
                        return redirect()->to('/admin/reports');
                    }
                    return;
                }
            }

            // Simpan URL yang dicoba diakses
            session()->set('redirect_url', current_url());

            return redirect()->to('auth');
        } else {
            // Jika sudah login, cek role pimpinan
            if (session()->get('role') === 'pimpinan') {
                // Arahkan ke halaman reports jika mencoba mengakses halaman lain
                $current_path = uri_string();
                $allowed_paths = [
                    'admin/reports',
                    'admin/reports/karyawan',
                    'admin/reports/paket',
                    'admin/reports/pelanggan',
                    'admin/reports/booking',
                    'admin/reports/pembayaran',
                    'admin/reports/pendapatan-bulanan',
                    'admin/reports/pendapatan-tahunan',
                    'admin/reports/pengeluaran',
                    'admin/reports/laba-rugi',
                    'admin/reports/laba-rugi-bulanan',
                    'auth/logout'
                ];

                if (!in_array($current_path, $allowed_paths)) {
                    return redirect()->to('admin/reports');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
