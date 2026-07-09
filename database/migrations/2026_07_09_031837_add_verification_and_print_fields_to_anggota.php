<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerificationAndPrintFieldsToAnggota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->string('no_kk', 16)->nullable()->after('nik');
            $table->string('foto_kk')->nullable()->after('foto_ktp');
            $table->string('nama_perusahaan')->nullable()->after('pekerjaan');
            $table->string('jabatan')->nullable()->after('nama_perusahaan');
            $table->text('alamat_kantor')->nullable()->after('jabatan');
            $table->string('nama_keluarga_dekat')->nullable()->after('status_rumah');
            $table->text('alamat_keluarga_dekat')->nullable()->after('nama_keluarga_dekat');
            $table->string('no_hp_keluarga_dekat', 15)->nullable()->after('alamat_keluarga_dekat');
        });

        Schema::create('cetak_kartu_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, diproses, selesai, ditolak
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cetak_kartu_requests');

        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn([
                'no_kk',
                'foto_kk',
                'nama_perusahaan',
                'jabatan',
                'alamat_kantor',
                'nama_keluarga_dekat',
                'alamat_keluarga_dekat',
                'no_hp_keluarga_dekat'
            ]);
        });
    }
}
