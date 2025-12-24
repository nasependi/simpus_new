<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LainnyaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add "Lainnya" to pekerjaan table if not exists
        $pekerjaan = DB::table('pekerjaan')
            ->where('nama_pekerjaan', 'Lainnya')
            ->first();
            
        if (!$pekerjaan) {
            DB::table('pekerjaan')->insert([
                'nama_pekerjaan' => 'Lainnya',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Add "Asuransi Lainnya" to cara_pembayaran table if not exists
        $caraPembayaran = DB::table('cara_pembayaran')
            ->where('nama', 'LIKE', '%Asuransi Lainnya%')
            ->first();
            
        if (!$caraPembayaran) {
            DB::table('cara_pembayaran')->insert([
                'nama' => 'Asuransi Lainnya',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
