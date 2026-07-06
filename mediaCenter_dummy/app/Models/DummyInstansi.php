<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyInstansi extends Model
{
    protected $table = 'dummy_instansis';

    protected $fillable = [
        'nama_instansi',
    ];
}