<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAnggotaTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggota', function (Blueprint $table) {
            // Drop kolom lama yang tidak dipakai
            $table->dropColumn(['no_ktp', 'foto']);
            
            // Tambah kolom baru dengan nullable untuk existing data
            $table->string('nik', 16)->nullable()->after('password');
            $table->enum('golongan_darah', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable()->after('jenis_kelamin');
            $table->text('alamat_jakarta')->nullable()->after('alamat');
            $table->enum('kota_bagian', ['Jakarta Utara', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Pusat', 'Kota Tangerang', 'Kabupaten Tangerang', 'Tangerang Selatan', 'Depok', 'Bekasi', 'Bogor'])->nullable()->after('alamat_jakarta');
            $table->string('asal_daerah')->nullable()->after('pekerjaan');
            $table->string('foto_ktp')->nullable()->after('asal_daerah');
            $table->string('nama_ortu')->nullable()->after('foto_ktp');
            $table->date('tanggal_lahir_ortu')->nullable()->after('nama_ortu');
            $table->string('tempat_lahir_ortu')->nullable()->after('tanggal_lahir_ortu');
            $table->enum('status_rumah', ['Rumah Tetap', 'Rumah Kontrak'])->nullable()->after('tempat_lahir_ortu');
        });
        
        // Add unique constraint after column is created
        Schema::table('anggota', function (Blueprint $table) {
            $table->unique('nik');
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
            $table->dropUnique(['nik']);
            $table->dropColumn(['nik', 'golongan_darah', 'alamat_jakarta', 'kota_bagian', 'asal_daerah', 'foto_ktp', 'nama_ortu', 'tanggal_lahir_ortu', 'tempat_lahir_ortu', 'status_rumah']);
            $table->string('no_ktp', 16)->unique();
            $table->string('foto')->nullable();
        });
    }
}
