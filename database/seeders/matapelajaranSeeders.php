<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class matapelajaranSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $matapelajaran = [
            'Matematika',
            'Fisika',
            'Kimia',
            'Biologi',
            'Bahasa Inggris',
            'Bahasa Indonesia',
            'Pendidikan Kewarganegaraan (PKn)',
            'Pendidikan Agama', // Sesuaikan dengan agama yang dianut
            'Sejarah',
            'Ilmu Pengetahuan Lingkungan',
            'Ekonomi',
            'Sosiologi',
            'Geografi',
            'Sejarah'
        ];

        foreach ($matapelajaran as $mapel) {
            Category::create([
                'id_sekolah_asal' => 1,
                'name_category' => $mapel,
                'kkm' => 75,
                'status' => 1,
            ]);
        }
    }
}
