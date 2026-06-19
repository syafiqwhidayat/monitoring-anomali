<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class ApiKeyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Ambil API Key dari header request
        $incomingKey = $request->getHeaderLine('X-API-KEY');

        // 2. Ambil token sah dari file .env
        $validKey = env('API_SECRET_KEY');

        // 3. Jika tidak cocok, langsung potong komando dan kembalikan respon JSON 401
        if (empty($incomingKey) || $incomingKey !== $validKey) {
            $response = Services::response();
            $response->setStatusCode(401);
            $response->setJSON([
                'status'  => 401,
                'error'   => 'Unauthorized',
                'messages' => [
                    'error' => 'Akses ditolak! API Key tidak valid atau tidak disertakan.'
                ]
            ]);

            // Mengembalikan objek response akan menghentikan eksekusi ke Controller
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu diisi untuk keperluan validasi di awal
    }
}
