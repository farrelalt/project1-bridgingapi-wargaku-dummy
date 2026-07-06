<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyKecamatan extends Model
{
    protected $table = 'dummy_kecamatans';

    protected $fillable = [
        'nama_kecamatan',
    ];
}