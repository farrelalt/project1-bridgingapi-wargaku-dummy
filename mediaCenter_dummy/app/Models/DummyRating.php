<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyRating extends Model
{
    protected $table = 'dummy_ratings';

    protected $fillable = [
        'keluhan_id',
        'responsivitas',
        'informasi',
        'tindak_lanjut',
        'keramahan',
        'kepuasan',
        'kritik_saran',
    ];
}