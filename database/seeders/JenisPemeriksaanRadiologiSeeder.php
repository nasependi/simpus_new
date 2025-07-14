<?php

use App\Models\JenisPemeriksaanRadiologi;
use Illuminate\Database\Seeder;

class JenisPemeriksaanRadiologiSeeder extends Seeder
{
    public function run(): void
    {
        JenisPemeriksaanRadiologi::insert([
            ['kode' => 'CR', 'nama' => 'Cranium'],
            ['kode' => 'GG', 'nama' => 'Gigi Geligi'],
            ['kode' => 'VB', 'nama' => 'Vertebra'],
            ['kode' => 'BD', 'nama' => 'Badan'],
            ['kode' => 'EA', 'nama' => 'Ekstremitas atas'],
            ['kode' => 'EB', 'nama' => 'Ekstremitas bawah'],
            ['kode' => 'KSC', 'nama' => 'Kontras Saluran Cerna'],
            ['kode' => 'KSK', 'nama' => 'Kontras Saluran Kencing'],
        ]);
    }
}
