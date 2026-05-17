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

        // Jika user belum memilih role (session kosong), Jika kosong, ambil dari database/grup user,pilih role yg paling pertama.
        if (! $activeRole) {
            $groups = auth()->user()->getGroups();
            session()->set('aktif_role', auth()->user()->getGroups()[0]);
            if (!empty($groups)) {
                $activeRole = $groups[0];
                session()->set('aktif_role', $activeRole);
            } else {
                // Jika user tidak punya grup sama sekali, logout atau arahkan ke error
                auth()->logout();
                return redirect()->to('/login')->with('error', 'User tidak memiliki role.');
            }
        }

        // CEK REDIRECT LOOP:
        // Jangan lakukan filter jika user sudah berada di halaman 'access-denied'
        if ($request->getUri()->getPath() == 'access-denied') {
            return;
        }

        // Cek otorisasi
        if ($arguments && !in_array($activeRole, $arguments)) {
            // Gunakan response interface untuk redirect yang lebih bersih di CI4
            return redirect()->to(base_url('access-denied'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
