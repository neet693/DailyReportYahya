<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitKerja::create([
            'name' => 'Yayasan',
        ]);
        UnitKerja::create([
            'name' => 'Tata Usaha',
        ]);
        UnitKerja::create([
            'name' => 'TK',
        ]);
        UnitKerja::create([
            'name' => 'SD',
        ]);
        UnitKerja::create([
            'name' => 'SMP',
        ]);
        UnitKerja::create([
            'name' => 'SMA',
        ]);
        UnitKerja::create([
            'name' => 'IT',
        ]);
        UnitKerja::create([
            'name' => 'Sarpras',
        ]);
        UnitKerja::create([
            'name' => 'Uncategorized Unit',
        ]);
    }
}
