<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Pastikan user sudah login
        if (!session()->get('logged_in')) {
            return redirect()->to('auth');
        }

        // Cek role
        $userRole = session()->get('role');

        // Jika tidak ada role yang diperlukan, izinkan akses
        if (empty($arguments)) {
            return;
        }

        // Jika role user sudah sesuai dengan yang diizinkan, berikan akses
        if (in_array($userRole, $arguments)) {
            return;
        }

        // Khusus untuk pimpinan
        if ($userRole === 'pimpinan') {
            // Dapatkan URI path saat ini
            $segments = $request->uri->getSegments();

            // Izinkan akses ke semua URL admin/reports/*
            if (count($segments) >= 2 && $segments[0] === 'admin' && $segments[1] === 'reports') {
                return;
            }

            // Redirect ke halaman laporan jika mencoba akses halaman lain
            return redirect()->to('admin/reports')->with('error', 'Anda hanya memiliki akses ke halaman laporan');
        }

        // Untuk role lain yang tidak memiliki akses
        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
