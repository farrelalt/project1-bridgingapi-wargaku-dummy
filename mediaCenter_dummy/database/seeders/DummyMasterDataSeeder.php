<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyMasterDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dummy_kategoris')->insert([
            ['nama_kategori' => 'Infrastruktur', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Kebersihan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Keamanan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Kesehatan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Penerangan Jalan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('dummy_channels')->insert([
            ['nama_channel' => 'Aplikasi Wargaku', 'created_at' => now(), 'updated_at' => now()],
            ['nama_channel' => 'Call Center 112', 'created_at' => now(), 'updated_at' => now()],
            ['nama_channel' => 'Instagram', 'created_at' => now(), 'updated_at' => now()],
            ['nama_channel' => 'Website', 'created_at' => now(), 'updated_at' => now()],
            ['nama_channel' => 'WhatsApp', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('dummy_kecamatans')->insert([
            ['nama_kecamatan' => 'Gubeng', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kecamatan' => 'Sukolilo', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kecamatan' => 'Rungkut', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kecamatan' => 'Wonokromo', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kecamatan' => 'Tambaksari', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('dummy_kelurahans')->insert([
            ['kecamatan_id' => 1, 'nama_kelurahan' => 'Kertajaya', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 1, 'nama_kelurahan' => 'Airlangga', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 2, 'nama_kelurahan' => 'Keputih', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 2, 'nama_kelurahan' => 'Gebang Putih', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama_kelurahan' => 'Rungkut Kidul', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 3, 'nama_kelurahan' => 'Medokan Ayu', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 4, 'nama_kelurahan' => 'Darmo', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 4, 'nama_kelurahan' => 'Jagir', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 5, 'nama_kelurahan' => 'Tambaksari', 'created_at' => now(), 'updated_at' => now()],
            ['kecamatan_id' => 5, 'nama_kelurahan' => 'Pacar Keling', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('dummy_topiks')->insert([
            ['nama_topik' => 'Jalan Rusak', 'created_at' => now(), 'updated_at' => now()],
            ['nama_topik' => 'Sampah Menumpuk', 'created_at' => now(), 'updated_at' => now()],
            ['nama_topik' => 'Lampu Jalan Mati', 'created_at' => now(), 'updated_at' => now()],
            ['nama_topik' => 'Banjir', 'created_at' => now(), 'updated_at' => now()],
            ['nama_topik' => 'Gangguan Ketertiban', 'created_at' => now(), 'updated_at' => now()],
            ['nama_topik' => 'Layanan Kesehatan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('dummy_statuses')->insert([
            ['nama_status' => 'Belum Ditanggapi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Diproses', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Sudah Ditindaklanjuti', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Selesai', 'created_at' => now(), 'updated_at' => now()],
            ['nama_status' => 'Ditolak', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('dummy_instansis')->insert([
            ['nama_instansi' => 'Dinas Sumber Daya Air dan Bina Marga', 'created_at' => now(), 'updated_at' => now()],
            ['nama_instansi' => 'Dinas Lingkungan Hidup', 'created_at' => now(), 'updated_at' => now()],
            ['nama_instansi' => 'Dinas Perhubungan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_instansi' => 'Satpol PP', 'created_at' => now(), 'updated_at' => now()],
            ['nama_instansi' => 'Dinas Kesehatan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_instansi' => 'Kecamatan Gubeng', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}