<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyTanggapanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dummy_tanggapans')->insert([
            [
                'keluhan_id' => 2,
                'tanggapan' => 'Laporan telah diterima dan sedang dikoordinasikan dengan petugas kebersihan wilayah Gubeng.',
                'foto' => null,
                'tanggal_tanggapan' => '2026-07-02',
                'tanggal_tindak_lanjut' => '2026-07-03',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keluhan_id' => 3,
                'tanggapan' => 'Petugas telah melakukan pengecekan lokasi dan perbaikan lampu jalan sedang dijadwalkan.',
                'foto' => null,
                'tanggal_tanggapan' => '2026-07-03',
                'tanggal_tindak_lanjut' => '2026-07-04',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keluhan_id' => 4,
                'tanggapan' => 'Saluran air telah dibersihkan oleh petugas. Genangan sudah berkurang dan laporan dinyatakan selesai.',
                'foto' => null,
                'tanggal_tanggapan' => '2026-07-04',
                'tanggal_tindak_lanjut' => '2026-07-05',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keluhan_id' => 5,
                'tanggapan' => 'Laporan tidak dapat diproses karena informasi lokasi kurang lengkap dan belum dapat diverifikasi.',
                'foto' => null,
                'tanggal_tanggapan' => '2026-07-05',
                'tanggal_tindak_lanjut' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}