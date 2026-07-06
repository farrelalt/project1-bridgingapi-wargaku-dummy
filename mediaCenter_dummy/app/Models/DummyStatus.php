<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyStatus extends Model
{
    protected $table = 'dummy_statuses';

    protected $fillable = [
        'nama_status',
    ];
}