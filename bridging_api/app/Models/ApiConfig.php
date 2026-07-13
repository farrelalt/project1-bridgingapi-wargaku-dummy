<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'request_mapping',
        'response_mapping',
        'response_mode',
    ];

    protected $casts = [
        'is_restricted' => 'boolean',
        'request_mapping' => 'array',
        'response_mapping' => 'array',
    ];

}