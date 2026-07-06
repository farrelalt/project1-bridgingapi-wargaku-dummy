<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DummyUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dummy_users')->insert([
            [
                'user' => 'akhdan',
                'password' => Hash::make('password123'),
                'nik' => '3578000000000001',
                'name' => 'Akhdan Sayudha',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '2003-01-01',
                'alamat' => 'Jl. Kertajaya, Surabaya',
                'kecamatan_id' => 1,
                'kelurahan_id' => 1,
                'phone' => '081234567890',
                'token' => 'dummy-token-akhdan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user' => 'farrel@example.com',
                'password' => Hash::make('password123'),
                'nik' => '3578000000000002',
                'name' => 'Farrel Mario',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '2003-02-02',
                'alamat' => 'Jl. Manyar, Surabaya',
                'kecamatan_id' => 1,
                'kelurahan_id' => 2,
                'phone' => '081234567891',
                'token' => 'dummy-token-farrel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user' => 'andini@example.com',
                'password' => Hash::make('password123'),
                'nik' => '3578000000000003',
                'name' => 'Andini Putri',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '2003-03-03',
                'alamat' => 'Jl. Rungkut, Surabaya',
                'kecamatan_id' => 3,
                'kelurahan_id' => 5,
                'phone' => '081234567892',
                'token' => 'dummy-token-andini',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user' => 'warga.sukolilo@example.com',
                'password' => Hash::make('password123'),
                'nik' => '3578000000000004',
                'name' => 'Budi Santoso',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '1998-04-10',
                'alamat' => 'Jl. Keputih, Surabaya',
                'kecamatan_id' => 2,
                'kelurahan_id' => 4,
                'phone' => '081234567893',
                'token' => 'dummy-token-budi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user' => 'warga.wonokromo@example.com',
                'password' => Hash::make('password123'),
                'nik' => '3578000000000005',
                'name' => 'Siti Aminah',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '1997-05-20',
                'alamat' => 'Jl. Wonokromo, Surabaya',
                'kecamatan_id' => 4,
                'kelurahan_id' => 7,
                'phone' => '081234567894',
                'token' => 'dummy-token-siti',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}