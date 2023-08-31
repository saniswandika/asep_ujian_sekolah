<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class guruImport implements ToModel, WithHeadingRow 
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new User([
            // 'name_sekolah' => $row['name_sekolah'],
            // 'no_induk' => $row['no_induk'],
            'role' => $row['role'],
            'no_induk' => $row['no_induk'],
            'n' => $row['n'],
            'jk' => $row['jk'],
            'gambar' => $row['gambar'],
            'sekolah_asal' => $row['sekolah_asal'],
            'name' => $row['name'],
            'username' => $row['username'],
            'password' => $row['password'],
        ]);
    }
}
