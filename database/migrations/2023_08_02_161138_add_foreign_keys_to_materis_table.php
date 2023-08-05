<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMaterisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materis', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kelas')->nullable();
            $table->unsignedBigInteger('id_category')->nullable();

            // Add foreign key constraints
            $table->foreign('id_kelas')->references('id')->on('kelas');
            $table->foreign('id_category')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materis', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['id_kelas']);
            $table->dropForeign(['id_category']);

            // Drop the columns
            $table->dropColumn('id_kelas');
            $table->dropColumn('id_category');
        });
    }
}
