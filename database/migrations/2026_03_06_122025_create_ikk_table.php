<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIkkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ikk', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 2)->unique();
            $table->string('nama');
            $table->timestamps();
        });

        // Insert data
        DB::table('ikk')->insert([
            ['kode' => '01', 'nama' => 'Kota Pekanbaru', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '02', 'nama' => 'Kota Dumai', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '03', 'nama' => 'Kabupaten Bengkalis', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '04', 'nama' => 'Kabupaten Indragiri Hilir', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '05', 'nama' => 'Kabupaten Indragiri Hulu', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '06', 'nama' => 'Kabupaten Kampar', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '07', 'nama' => 'Kabupaten Kepulauan Meranti', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '08', 'nama' => 'Kabupaten Kuantan Singingi', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '09', 'nama' => 'Kabupaten Pelalawan', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '10', 'nama' => 'Kabupaten Rokan Hilir', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '11', 'nama' => 'Kabupaten Rokan Hulu', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => '12', 'nama' => 'Kabupaten Siak', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ikk');
    }
}