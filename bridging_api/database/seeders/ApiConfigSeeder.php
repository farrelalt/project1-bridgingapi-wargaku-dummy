<?php

namespace Database\Seeders;

use App\Models\ApiConfig;
use Illuminate\Database\Seeder;

class ApiConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            [
                'service_name' => 'Login',
                'local_endpoint' => '/api/v2/login',
                'target_endpoint' => config('services.media_center.endpoints.login'),
                'method' => 'POST',
                'status' => 'active',
                'is_restricted' => false,
                'description' => 'Login pengguna Wargaku melalui Media Center.',
            ],
            [
                'service_name' => 'Register',
                'local_endpoint' => '/api/v2/register',
                'target_endpoint' => config('services.media_center.endpoints.register'),
                'method' => 'POST',
                'status' => 'active',
                'is_restricted' => false,
                'description' => 'Registrasi pengguna baru.',
            ],
            [
                'service_name' => 'Profile',
                'local_endpoint' => '/api/v2/profile',
                'target_endpoint' => config('services.media_center.endpoints.profile'),
                'method' => 'GET',
                'status' => 'active',
                'is_restricted' => true,
                'description' => 'Mengambil data profil pengguna.',
            ],

            [
                'service_name' => 'Kategori',
                'local_endpoint' => '/api/v2/kategori',
                'target_endpoint' => config('services.media_center.endpoints.kategori'),
                'method' => 'GET',
                'status' => 'active',
                'is_restricted' => false,
                'description' => 'Mengambil daftar kategori keluhan.',
            ],
            [
                'service_name' => 'Chanel',
                'local_endpoint' => '/api/v2/chanel',
                'target_endpoint' => config('services.media_center.endpoints.chanel'),
                'method' => 'GET',
                'status' => 'active',
                'is_restricted' => false,
                'description' => 'Mengambil daftar channel pengaduan.',
            ],
            [
                'service_name' => 'Kecamatan',
                'local_endpoint' => '/api/v2/kecamatan',
                'target_endpoint' => config('services.media_center.endpoints.kecamatan'),
                'method' => 'GET',
                'status' => 'active',
                'is_restricted' => false,
                'description' => 'Mengambil daftar kecamatan.',
            ],
            [
                'service_name' => 'Kelurahan',
                'local_endpoint' => '/api/v2/kelurahan/{id_kec}',
                'target_endpoint' => config('services.media_center.endpoints.kelurahan'),
                'method' => 'GET',
                'status' => 'active',
                'is_restricted' => false,
                'description' => 'Mengambil daftar kelurahan berdasarkan kecamatan.',
            ],
            [
                'service_name' => 'Topik',
                'local_endpoint' => '/api/v2/topik',
                'target_endpoint' => config('services.media_center.endpoints.topik'),
                'method' => 'GET',
                'status' => 'active',
                'is_restricted' => false,
                'description' => 'Mengambil daftar topik keluhan.',
            ],
            [
                'service_name' => 'Status',
                'local_endpoint' => '/api/v2/status',
                'target_endpoint' => config('services.media_center.endpoints.status'),
                'method' => 'GET',
                'status' => 'active',
                'is_restricted' => false,
                'description' => 'Mengambil daftar status keluhan.',
            ],
            [
                'service_name' => 'Instansi',
                'local_endpoint' => '/api/v2/instansi',
                'target_endpoint' => config('services.media_center.endpoints.instansi'),
                'method' => 'POST',
                'status' => 'active',
                'is_restricted' => false,
                'description' => 'Mengambil atau menentukan instansi berdasarkan parameter tertentu.',
            ],

            [
                'service_name' => 'Keluhan Create',
                'local_endpoint' => '/api/v2/keluhan/create',
                'target_endpoint' => config('services.media_center.endpoints.keluhan_create'),
                'method' => 'POST',
                'status' => 'active',
                'is_restricted' => true,
                'description' => 'Membuat keluhan baru.',
            ],
            [
                'service_name' => 'Keluhan List',
                'local_endpoint' => '/api/v2/keluhan',
                'target_endpoint' => config('services.media_center.endpoints.keluhan'),
                'method' => 'POST',
                'status' => 'active',
                'is_restricted' => true,
                'description' => 'Mengambil daftar keluhan.',
            ],
            [
                'service_name' => 'Keluhan Detail',
                'local_endpoint' => '/api/v2/keluhan/{id}',
                'target_endpoint' => config('services.media_center.endpoints.keluhan_detail'),
                'method' => 'GET',
                'status' => 'active',
                'is_restricted' => true,
                'description' => 'Mengambil detail keluhan berdasarkan ID.',
            ],
            [
                'service_name' => 'Keluhan Selesai',
                'local_endpoint' => '/api/v2/keluhan/selesai',
                'target_endpoint' => config('services.media_center.endpoints.keluhan_selesai'),
                'method' => 'POST',
                'status' => 'active',
                'is_restricted' => true,
                'description' => 'Menandai keluhan sebagai selesai.',
                 'request_mapping' => [
                    'keluhan_id' => 'id_keluhan',],
            ],
            [
                'service_name' => 'Keluhan Hapus',
                'local_endpoint' => '/api/v2/keluhan/hapus',
                'target_endpoint' => config('services.media_center.endpoints.keluhan_hapus'),
                'method' => 'POST',
                'status' => 'active',
                'is_restricted' => true,
                'description' => 'Menghapus data keluhan.',
            ],

            [
                'service_name' => 'Tanggapan Create',
                'local_endpoint' => '/api/v2/tanggapan/create',
                'target_endpoint' => config('services.media_center.endpoints.tanggapan_create'),
                'method' => 'POST',
                'status' => 'active',
                'is_restricted' => true,
                'description' => 'Membuat tanggapan pada keluhan.',
            ],
            [
                'service_name' => 'Tanggapan List',
                'local_endpoint' => '/api/v2/tanggapan',
                'target_endpoint' => config('services.media_center.endpoints.tanggapan'),
                'method' => 'POST',
                'status' => 'active',
                'is_restricted' => true,
                'description' => 'Mengambil daftar tanggapan keluhan.',
            ],

            [
                'service_name' => 'Keluhan Rating',
                'local_endpoint' => '/api/v2/keluhan/rating',
                'target_endpoint' => config('services.media_center.endpoints.keluhan_rating'),
                'method' => 'POST',
                'status' => 'active',
                'is_restricted' => true,
                'description' => 'Memberikan rating terhadap penyelesaian keluhan.',
            ],
            [
                'service_name' => 'View Keluhan Rating',
                'local_endpoint' => '/api/v2/keluhan/rating',
                'target_endpoint' => config('services.media_center.endpoints.view_keluhan_rating'),
                'method' => 'GET',
                'status' => 'active',
                'is_restricted' => true,
                'description' => 'Melihat rating keluhan.',
            ],

            /*
            |--------------------------------------------------------------------------
            | Endpoint Legacy untuk pengujian response mapping
            |--------------------------------------------------------------------------
            */

            [
                'service_name' => 'Kategori Legacy Test',
                'local_endpoint' => '/api/v2/kategori-legacy',
                'target_endpoint' => '/api/kategori',
                'method' => 'GET',
                'status' => 'active',
                'is_restricted' => false,
                'description' => 'Pengujian response mapping kategori mode legacy.',
                'request_mapping' => null,
                'response_mapping' => [
                    'success' => 'status',
                ],
                'response_mode' => 'legacy',
            ],

            /*
            |--------------------------------------------------------------------------
            | Endpoint Legacy untuk request dan response mapping
            |--------------------------------------------------------------------------
            */

            [
                'service_name' => 'Keluhan Legacy Test',
                'local_endpoint' => '/api/v2/keluhan-legacy',
                'target_endpoint' => '/api/keluhan_create',
                'method' => 'POST',
                'status' => 'active',
                'is_restricted' => false,
                'description' => 'Pengujian request dan response mapping keluhan mode legacy.',
                'request_mapping' => [
                    'pengaduan' => 'keluhan',
                ],
                'response_mapping' => [
                    'success' => 'status',
                    'keluhan' => 'pengaduan',
                ],
                'response_mode' => 'legacy',
            ],
        ];

        foreach ($configs as $config) {
            ApiConfig::updateOrCreate(
                [
                    'local_endpoint' => $config['local_endpoint'],
                    'method' => $config['method'],
                ],
                $config
            );
        }
    }
}