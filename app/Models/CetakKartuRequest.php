<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CetakKartuRequest extends Model
{
    use HasFactory;

    protected $table = 'cetak_kartu_requests';

    protected $fillable = [
        'anggota_id',
        'status',
        'keterangan'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }
}
