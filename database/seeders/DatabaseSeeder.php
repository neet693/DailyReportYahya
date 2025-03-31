<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use App\Models\EmploymentDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder UnitKerjaSeeder terlebih dahulu
        $this->call([
            UnitKerjaSeeder::class,
        ]);

        // Ambil Unit "Uncategorized Unit"
        $uncategorizedUnit = UnitKerja::where('name', 'Uncategorized Unit')->first();

        // Buat User Aloy
        $aloy = User::create([
            'name' => 'Aloy',
            'email' => 'aloy@dailyreportyahya.com',
            'password' => bcrypt('rusakdeh'), // Ganti dengan kata sandi yang aman
            'role' => 'kepala',
            'address' => 'Alamat',
            'gender' => '',
        ]);

        // Tambahkan data Employment Detail untuk Aloy
        if ($uncategorizedUnit) {
            EmploymentDetail::create([
                'user_id' => $aloy->id,
                'unit_kerja_id' => $uncategorizedUnit->id,
                'employee_number' => 'EMP-001', // Bisa diganti sesuai kebutuhan
                'tahun_masuk' => '2024',
                'tahun_sertifikasi' => '2024',
                'status_kepegawaian' => 'Tetap',
            ]);
        }

        // Buat User Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@dailyreportyahya.com',
            'password' => bcrypt('rusakdeh'), // Ganti dengan kata sandi yang aman
            'role' => 'admin',
            'address' => 'Alamat',
            'gender' => '',
        ]);
    }
}
