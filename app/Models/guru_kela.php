<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class guru_kela extends Model
{
    use HasFactory;
    protected $table = 'user_classes';
    protected $fillable = ([
       'class_id',
       'user_id'
        // 'capaian_kompetensi',
    ]);
    public function user()
    {
        return $this->belongsTo(kelas::class, 'id');
    }

}
