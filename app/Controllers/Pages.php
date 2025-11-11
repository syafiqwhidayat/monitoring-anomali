<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Home | Monitoring Anomali',
            'data' => ['satu', 'dua', 'tiga']
        ];
        return view('pages/home', $data);
    }
    public function about()
    {
        $data = [
            'title' => 'About | Monitoring Anomali'
        ];
        return view('pages/about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact Us',
            'alamat' => [
                [
                    'tipe' => 'Rumah',
                    'alamat' => 'Jl. abc no 123',
                    'kota' => 'bandung'
                ],
                [
                    'tipe' => 'Kantor',
                    'alamat' => 'jalan setia budi',
                    'kota' => 'Bandung'
                ]
            ]

        ];

        return view('pages/contact', $data);
    }
}
