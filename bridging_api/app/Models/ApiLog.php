<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'service_name',
        'local_endpoint',
        'target_endpoint',
        'method',
        'request_payload',
        'response_payload',
        'status_code',
        'is_success',
        'error_message',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'is_success' => 'boolean',
    ];
}