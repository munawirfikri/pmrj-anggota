<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Anggota extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'anggota';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'golongan_darah',
        'nik',
        'pekerjaan',
        'alamat_jakarta',
        'kota_bagian',
        'no_telepon',
        'foto_ktp',
        'foto',
        'nama_ortu',
        'tanggal_lahir_ortu',
        'tempat_lahir_ortu',
        'status_rumah',
        'no_anggota',
        'status'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_lahir_ortu' => 'date',
    ];

    public function generateNoAnggota()
    {
        $year = date('y'); // 2 digit tahun
        $lastMember = self::whereNotNull('no_anggota')
                         ->where('no_anggota', '!=', '')
                         ->orderBy('id', 'desc')
                         ->first();
        
        if ($lastMember && !empty($lastMember->no_anggota)) {
            $lastNumber = intval(substr($lastMember->no_anggota, -6));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return "PMRJ{$year}" . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
}
