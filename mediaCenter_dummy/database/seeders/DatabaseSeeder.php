<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DummyMasterDataSeeder::class,
            DummyUserSeeder::class,
            DummyKeluhanSeeder::class,
            DummyTanggapanSeeder::class,
            DummyRatingSeeder::class,
        ]);
    }
}