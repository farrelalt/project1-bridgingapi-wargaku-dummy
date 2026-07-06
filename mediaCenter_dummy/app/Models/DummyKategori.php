<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyKategori extends Model
{
    protected $table = 'dummy_kategoris';

    protected $fillable = [
        'nama_kategori',
    ];
}