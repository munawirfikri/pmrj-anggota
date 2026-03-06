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
        'asal_ikk',
        'no_hp',
        'foto_ktp',
        'foto',
        'status_rumah',
        'no_anggota',
        'status'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function ikk()
    {
        return $this->belongsTo(Ikk::class, 'asal_ikk', 'nama');
    }

    public function generateNoAnggota()
    {
        $ikk = Ikk::where('nama', $this->asal_ikk)->first();
        $ikkCode = $ikk ? $ikk->kode : '00';
        
        // Get the highest number from all existing member numbers
        $lastMember = self::whereNotNull('no_anggota')
                         ->where('no_anggota', '!=', '')
                         ->where('no_anggota', 'REGEXP', '^PMRJ-[0-9]{2}-[0-9]{4}$')
                         ->orderByRaw('CAST(SUBSTRING(no_anggota, -4) AS UNSIGNED) DESC')
                         ->first();
        
        if ($lastMember && !empty($lastMember->no_anggota)) {
            $lastNumber = intval(substr($lastMember->no_anggota, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return "PMRJ-{$ikkCode}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
