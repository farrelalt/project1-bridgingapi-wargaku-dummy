<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyUser extends Model
{
    protected $table = 'dummy_users';

    protected $fillable = [
        'user',
        'password',
        'nik',
        'name',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'kecamatan_id',
        'kelurahan_id',
        'phone',
        'token',
    ];

    protected $hidden = [
        'password',
    ];
}