<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterTables extends Migration
{
    public function up()
    {
        // Jenis Kelamin
        Schema::create('jenis_kelamin', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        // Golongan Darah
        Schema::create('golongan_darah', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        // Kota Bagian
        Schema::create('kota_bagian', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        // Status Rumah
        Schema::create('status_rumah', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('status_rumah');
        Schema::dropIfExists('kota_bagian');
        Schema::dropIfExists('golongan_darah');
        Schema::dropIfExists('jenis_kelamin');
    }
}