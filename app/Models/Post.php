<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = ([
        'id_user','id_kelas','id_sekolah_asal','id_category', 'soal_ujian', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'jawaban','id_category_ujian',
    ]);

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }
    public function category_ujian()
    {
        return $this->belongsTo(CategoryUjian::class, 'id_category_ujian');
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah_asal');
    }

}
