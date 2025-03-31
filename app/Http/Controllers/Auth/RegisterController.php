<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\EmploymentDetail;
use App\Models\UnitKerja;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        // Cari unit kerja "Uncategorized Unit"
        $uncategorizedUnit = UnitKerja::where('name', 'Uncategorized Unit')->first();

        // Buat user baru
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Tambahkan employment_detail jika unit ditemukan
        if ($uncategorizedUnit) {
            EmploymentDetail::create([
                'user_id' => $user->id,
                'unit_kerja_id' => $uncategorizedUnit->id,
                'employee_number' => 'EMP-' . str_pad($user->id, 3, '0', STR_PAD_LEFT), // Auto generate nomor pegawai
                'tahun_masuk' => now()->year,
                'tahun_sertifikasi' => now()->year,
                'status_kepegawaian' => 'Kontrak',
            ]);
        }

        return $user;
    }
}
