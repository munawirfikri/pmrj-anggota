<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateJenisKelaminConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE anggota DROP CONSTRAINT anggota_jenis_kelamin_check");
        DB::statement("ALTER TABLE anggota ADD CONSTRAINT anggota_jenis_kelamin_check CHECK (jenis_kelamin IN ('Laki-laki', 'Perempuan'))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE anggota DROP CONSTRAINT anggota_jenis_kelamin_check");
        DB::statement("ALTER TABLE anggota ADD CONSTRAINT anggota_jenis_kelamin_check CHECK (jenis_kelamin IN ('L', 'P'))");
    }
}
