<?php

namespace App\Imports;

use App\Models\user;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class siswaImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new user([
            // 'name_sekolah' => $row['name_sekolah'],
            'id_kelas' => $row['id_kelas'],
            'role' => $row['role'],
            'no_induk' => $row['no_induk'],
            'nisn' => $row['nisn'],
            'jk' => $row['jk'],
            'gambar' => $row['gambar'],
            'sekolah_asal' => $row['sekolah_asal'],
            'name' => $row['name'],
            'username' => $row['username'],
            'password' => $row['password'],
        ]);
    }
}
