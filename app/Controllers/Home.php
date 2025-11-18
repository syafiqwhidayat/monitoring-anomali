<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Monitoring Anomali'
        ];
        return view('indexMonan', $data);
    }
}
