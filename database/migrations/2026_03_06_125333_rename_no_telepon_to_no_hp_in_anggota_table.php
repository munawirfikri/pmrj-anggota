<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameNoTeleponToNoHpInAnggotaTable extends Migration
{
    public function up()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->renameColumn('no_telepon', 'no_hp');
        });
    }

    public function down()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->renameColumn('no_hp', 'no_telepon');
        });
    }
}