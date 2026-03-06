<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeAnggotaColumnsNullable extends Migration
{
    public function up()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('nik', 16)->nullable()->change();
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->text('alamat_jakarta')->nullable()->change();
            $table->string('no_telepon', 15)->nullable()->change();
            $table->string('pekerjaan')->nullable()->change();
            $table->string('foto_ktp')->nullable()->change();
            $table->string('foto')->nullable()->change();
        });
        
        // Handle enum columns separately
        DB::statement('ALTER TABLE anggota ALTER COLUMN jenis_kelamin DROP NOT NULL');
        DB::statement('ALTER TABLE anggota ALTER COLUMN golongan_darah DROP NOT NULL');
        DB::statement('ALTER TABLE anggota ALTER COLUMN kota_bagian DROP NOT NULL');
        DB::statement('ALTER TABLE anggota ALTER COLUMN status_rumah DROP NOT NULL');
    }

    public function down()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
            $table->string('nik', 16)->nullable(false)->change();
            $table->string('tempat_lahir')->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->text('alamat_jakarta')->nullable(false)->change();
            $table->string('no_telepon', 15)->nullable(false)->change();
            $table->string('pekerjaan')->nullable(false)->change();
            $table->string('foto_ktp')->nullable(false)->change();
            $table->string('foto')->nullable(false)->change();
        });
        
        DB::statement('ALTER TABLE anggota ALTER COLUMN jenis_kelamin SET NOT NULL');
        DB::statement('ALTER TABLE anggota ALTER COLUMN golongan_darah SET NOT NULL');
        DB::statement('ALTER TABLE anggota ALTER COLUMN kota_bagian SET NOT NULL');
        DB::statement('ALTER TABLE anggota ALTER COLUMN status_rumah SET NOT NULL');
    }
}