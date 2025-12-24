<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PekerjaanLainnyaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add "Lainnya" to pekerjaan table if not exists
        \DB::table('pekerjaan')->updateOrInsert(
            ['kode' => '99'],
            [
                'kode' => '99',
                'nama_pekerjaan' => 'Lainnya',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Add "Asuransi Lainnya" to cara_pembayaran table if not exists
        \DB::table('cara_pembayaran')->updateOrInsert(
            ['nama' => 'Asuransi Lainnya'],
            [
                'nama' => 'Asuransi Lainnya',
                'keterangan' => 'Asuransi selain JKN dan Mandiri',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
