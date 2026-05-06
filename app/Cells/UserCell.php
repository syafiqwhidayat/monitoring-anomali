<?php

namespace App\Cells;

class UserCell
{
    // Fungsi ini akan mengambil data secara mandiri
    public function dropdown()
    {
        // mendapatkan daftar kegiatan aktif
        $data['list_role'] = auth()->user()->getGroups();
        $data['role_aktif'] = session()->get('role_aktif');

        return view('layout/cells/dropdown_role', $data);
    }
}
