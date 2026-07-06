<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyTopik extends Model
{
    protected $table = 'dummy_topiks';

    protected $fillable = [
        'nama_topik',
    ];
}