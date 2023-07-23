<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlamatSekolahToSekolahs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sekolahs', function (Blueprint $table) {
            // Menambahkan field alamat_sekolah dengan tipe data string
            $table->string('alamat_sekolah')->nullable()->after('name_sekolah');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sekolahs', function (Blueprint $table) {
            // Menghapus field alamat_sekolah jika migration di-rollback
            $table->dropColumn('alamat_sekolah');
        });
    }
}
