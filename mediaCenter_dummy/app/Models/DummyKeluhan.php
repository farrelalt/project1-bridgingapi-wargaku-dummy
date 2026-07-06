<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyKeluhan extends Model
{
    protected $table = 'dummy_keluhans';

    protected $fillable = [
        'user_id',
        'judul',
        'keluhan',
        'konten',
        'tanggal_keluhan',
        'kecamatan_id',
        'kelurahan_id',
        'kategori_id',
        'topik_id',
        'instansi_id',
        'channel_id',
        'status_id',
        'latitude',
        'longitude',
        'alamat',
        'sosial_media',
        'jenis_kelamin',
        'nomor_telepon',
        'nama',
    ];
}