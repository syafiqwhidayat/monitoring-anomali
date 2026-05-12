<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ActiveRoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        // Ambil role yang sedang aktif di session
        $activeRole = session()->get('aktif_role');

        // Jika user belum memilih role (session kosong), pilih role yg paling pertama.
        if (! $activeRole) {
            session()->set('aktif_role', auth()->user()->getGroups()[0]);
        }

        // Cek apakah role di session ada di dalam daftar role yang diizinkan di Route
        if ($arguments && ! in_array($activeRole, $arguments)) {
            // return redirect()->to('/dashboard')->with('error', 'Akses ditolak untuk role saat ini.');
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Anda tidak memiliki akses sebagai $activeRole");
            return redirect()->to('/access-denied');
            exit;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
