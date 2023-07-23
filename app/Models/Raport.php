<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raport extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_ujian_sekolah',
        // Add other attributes related to Raport if needed
    ];

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relationship with UjianSekolah model
    public function ujianSekolah()
    {
        return $this->belongsTo(UjianSekolah::class, 'id_ujian_sekolah');
    }
}
