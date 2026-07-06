<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiConfig extends Model
{
    protected $fillable = [
        'service_name',
        'local_endpoint',
        'target_endpoint',
        'method',
        'status',
        'is_restricted',
        'description',
    ];

    protected $casts = [
        'is_restricted' => 'boolean',
    ];
}