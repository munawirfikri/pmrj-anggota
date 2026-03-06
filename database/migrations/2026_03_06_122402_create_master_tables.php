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

        // Insert data
        DB::table('jenis_kelamin')->insert([
            ['nama' => 'Laki-laki', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Perempuan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('golongan_darah')->insert([
            ['nama' => 'A+', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'A-', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'B+', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'B-', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'AB+', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'AB-', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'O+', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'O-', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('kota_bagian')->insert([
            ['nama' => 'Jakarta Utara', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Jakarta Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Jakarta Barat', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Jakarta Timur', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Jakarta Pusat', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kota Tangerang', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kabupaten Tangerang', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Tangerang Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Depok', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Bekasi', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Bogor', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('status_rumah')->insert([
            ['nama' => 'Rumah Tetap', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Rumah Kontrak', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('status_rumah');
        Schema::dropIfExists('kota_bagian');
        Schema::dropIfExists('golongan_darah');
        Schema::dropIfExists('jenis_kelamin');
    }
}