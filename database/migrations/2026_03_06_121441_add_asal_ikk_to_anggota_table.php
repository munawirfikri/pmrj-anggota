<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAsalIkkToAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->enum('asal_ikk', [
                'Kota Pekanbaru',
                'Kota Dumai',
                'Kabupaten Bengkalis',
                'Kabupaten Indragiri Hilir',
                'Kabupaten Indragiri Hulu',
                'Kabupaten Kampar',
                'Kabupaten Kepulauan Meranti',
                'Kabupaten Kuantan Singingi',
                'Kabupaten Pelalawan',
                'Kabupaten Rokan Hilir',
                'Kabupaten Rokan Hulu',
                'Kabupaten Siak'
            ])->nullable()->after('kota_bagian');
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
            $table->dropColumn('asal_ikk');
        });
    }
}