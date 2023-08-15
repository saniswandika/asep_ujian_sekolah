<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'predikat', 'keterangan', 'id_user'];

    public function user()
{
    return $this->belongsTo(User::class, 'id_user');
}

}
