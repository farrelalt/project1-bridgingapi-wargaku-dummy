<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyRatingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dummy_ratings')->insert([
            [
                'keluhan_id' => 4,
                'responsivitas' => 5,
                'informasi' => 4,
                'tindak_lanjut' => 5,
                'keramahan' => 5,
                'kepuasan' => 5,
                'kritik_saran' => 'Pelayanan cepat dan laporan ditindaklanjuti dengan baik.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keluhan_id' => 3,
                'responsivitas' => 4,
                'informasi' => 4,
                'tindak_lanjut' => 4,
                'keramahan' => 5,
                'kepuasan' => 4,
                'kritik_saran' => 'Informasi sudah jelas, semoga perbaikan bisa lebih cepat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}