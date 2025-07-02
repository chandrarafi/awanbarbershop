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

        // Jika role user tidak sesuai dengan yang diizinkan
        if (!in_array($userRole, $arguments)) {
            // Jika role adalah pimpinan, arahkan ke halaman reports
            if ($userRole === 'pimpinan') {
                return redirect()->to('admin/reports')->with('error', 'Anda hanya memiliki akses ke halaman laporan');
            }
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
