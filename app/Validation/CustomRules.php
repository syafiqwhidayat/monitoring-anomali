<?php

namespace App\Validation;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;
use Config\Database;

class CustomRules extends BaseConfig
{
    /**
     * Memeriksa apakah kombinasi dari dua field adalah unik dalam sebuah tabel.
     * Penggunaan: custom_unique[nama_tabel.field_lain]
     * * @param string $str Nilai field yang sedang divalidasi (field_saat_ini)
     * @param string $field Parameter aturan (nama_tabel.field_lain)
     * @param array $data Semua data input dari form
     * @return bool
     */

    /**
     * Custom Rule: isUniqueInScope
     *
     * Format pemakaian:
     * isUniqueInScope[table,scopeField,scopeFieldInInput,uniqueField,primaryKey]
     *
     * Contoh:
     * isUniqueInScope[kategori_anomali,id_kegiatan,id_kegiatan,kode_anomali,id]
     *
     * Artinya:
     * - Cek pada tabel "kategori_anomali"
     * - Field "kode_anomali" harus unik
     * - Uniknya berdasarkan scope = id_kegiatan
     * - Nilai id_kegiatan diambil dari input user
     * - Jika update, abaikan record yang memiliki primary key = id
     */

    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    public $customErrors = [
        'uniqueWith' => 'Nilai ini sudah dipakai pada scope yang sama.',
    ];

    public function uniqueWith(string $value, string $params, array $data): bool
    {
        // Memecah parameter rule menjadi array
        // [table, scopeField, scopeFieldInInput, uniqueField, primaryKey]
        // dd($params);
        // dd($data);
        [$table, $scopeField, $uniqueField] = explode('.', $params);

        // Menghubungkan ke database
        $db = Database::connect();
        $data['id_kegiatan'] = $db->table($table)->select('id_kegiatan')->where('id', $data['id'])->get()->getFirstRow()->id_kegiatan;
        // dd($data[$scopeField]);

        // Membuat builder query
        $builder = $db->table($table)

            // Kondisi: scope harus sama (contoh: id_kegiatan = 5)
            ->where($scopeField, $data[$scopeField])

            // Kondisi: nilai yang unik tidak boleh duplikat (contoh: kode_anomali = "A01")
            ->where($uniqueField, $value);

        // Jika ini mode update, dan user mengirimkan primary key
        // Maka record dengan primary key itu diabaikan
        if (isset($data['id']) && $data['id'] != '') {

            // Contoh: WHERE id != 7
            $builder->where('id' . ' !=', $data['id']);
        }

        // Hitung jumlah record yang cocok
        // Jika hasil > 0 berarti duplikat
        // dd($data);
        return $builder->countAllResults() === 0;
    }
}
