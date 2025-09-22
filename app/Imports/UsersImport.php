<?php

namespace App\Imports;

use App\Models\EmploymentDetail;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class UsersImport implements ToModel, WithHeadingRow, WithMapping, WithCalculatedFormulas
{
    private $rowData;

    public function map($row): array
    {
        foreach ($row as $key => $value) {
            $row[$key] = is_null($value) ? null : trim((string) $value);
        }

        $this->rowData = $row;
        return $row;
    }

    public function model(array $row)
    {
        $get = fn($key, $default = null) => $this->rowData[$key] ?? $default;

        // Update or create user based on email
        $user = User::updateOrCreate(
            ['email' => $get('email')],
            [
                'name'           => $get('name'),
                'password' => Hash::make($get('password', env('DEFAULT_USER_PASSWORD'))),
                'role'           => 'pegawai',
                'birth_date'     => $get('birth_date'),
                'marital_status' => $get('marital_status'),
                'address'        => $get('address'),
                'gender'         => $get('gender'),
                'profile_image'  => $this->convertGoogleDriveLink($get('profile_image')),
            ]
        );

        $unitKerja = UnitKerja::where('name', $get('unit_kerja'))->first()
            ?? UnitKerja::where('name', 'Uncategorized Unit')->first();

        if ($unitKerja) {
            EmploymentDetail::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'unit_kerja_id'      => $unitKerja->id,
                    'employee_number'    => 'EMP-' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
                    'tahun_masuk'        => $get('tahun_masuk', now()->year),
                    'tahun_sertifikasi'  => $get('tahun_sertifikasi', now()->year),
                    'status_kepegawaian' => $get('status_kepegawaian', 'Kontrak'),
                ]
            );
        }

        return $user;
    }

    private function convertGoogleDriveLink($link)
    {
        if (!$link) return null;

        if (strpos($link, 'drive.google.com') !== false) {
            if (preg_match('/\/d\/(.*?)\//', $link, $matches)) {
                return 'https://drive.google.com/uc?id=' . $matches[1];
            }
        }

        return $link;
    }
}
