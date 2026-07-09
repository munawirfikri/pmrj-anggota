<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
}
