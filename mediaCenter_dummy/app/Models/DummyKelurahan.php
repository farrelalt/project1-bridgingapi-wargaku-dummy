<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyKelurahan extends Model
{
    protected $table = 'dummy_kelurahans';

    protected $fillable = [
        'kecamatan_id',
        'nama_kelurahan',
    ];
}