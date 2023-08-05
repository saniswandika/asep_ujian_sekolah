<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materis extends Model
{
    use HasFactory;
    protected $table = "materis";

    protected $fillable = ['file','keterangan', 'id_kelas', 'id_category', 'id_user'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}


