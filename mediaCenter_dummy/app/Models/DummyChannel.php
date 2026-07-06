<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyChannel extends Model
{
    protected $table = 'dummy_channels';

    protected $fillable = [
        'nama_channel',
    ];
}