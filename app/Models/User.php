<?php

namespace App\Models;

use App\Models\Post;


use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\UjianSekolah;
use App\Models\Category;
use App\Models\Semester;
// use Laravel\Passport\HasApiTokens;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;


    class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, AuthenticationLoggable;

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'no_induk',
        'jk',
        'nisn',
        'sekolah_asal',
        'password',
        'role',
        'nips',
        'id_semester'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->belongsTo(Post::class,'id_user','id_category', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class,'id_kelas');

        // Wali kelas banyak dipakai oleh kelas
        return $this->hasMany(Kelas::class,'id_wali');
    }
    public function guru_ngajar()
    {
        return $this->belongsTo(guru_kela::class,'user_id');

        // Wali kelas banyak dipakai oleh kelas
        return $this->hasMany(guru_kela::class,'user_id');
    }

    public function ujiansekolah()
    {

        return $this->hasMany(UjianSekolah::class,'id_user');
    }

   public function sekolah()
    {
        return $this->belongsTo(Sekolah::class,'sekolah_asal');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'posts', 'id_user', 'id_category');
    }

    public function dataUjian()
    {
        return $this->hasMany(DataUjian::class, 'id_user');
    }

        public function raport()
    {
        return $this->hasOne(Raport::class, 'id_user');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }

}
