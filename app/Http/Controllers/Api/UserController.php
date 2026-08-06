<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{

    /**
     * Cari user berdasarkan nomor phone
     */
    public function findByPhone($phone)
    {

        $user = User::where('phone', $phone)->first();


        if (!$user) {

            return response()->json([
                'message' => 'User tidak ditemukan'
            ], 404);
        }


        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);
    }

    public function units(User $user)
    {

        return response()->json([
            'data' => $user->units()->get([
                'unit_kerjas.id',
                'unit_kerjas.name'
            ])
        ]);
    }
}
