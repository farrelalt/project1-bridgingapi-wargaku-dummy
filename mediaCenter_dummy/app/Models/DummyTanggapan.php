<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyTanggapan extends Model
{
    protected $table = 'dummy_tanggapans';

    protected $fillable = [
        'keluhan_id',
        'tanggapan',
        'foto',
        'tanggal_tanggapan',
        'tanggal_tindak_lanjut',
    ];
}