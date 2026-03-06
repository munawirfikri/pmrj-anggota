<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveParentFieldsFromAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn(['nama_ortu', 'tanggal_lahir_ortu', 'tempat_lahir_ortu']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->string('nama_ortu')->nullable()->after('foto_ktp');
            $table->date('tanggal_lahir_ortu')->nullable()->after('nama_ortu');
            $table->string('tempat_lahir_ortu')->nullable()->after('tanggal_lahir_ortu');
        });
    }
}