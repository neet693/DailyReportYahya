<?php

namespace App\Policies;

use App\Models\PermissionRequest;
use App\Models\User;

class PermissionRequestPolicy
{
    /**
     * Create a new policy instance.
     */
    public function approve(User $user, PermissionRequest $permissionRequest)
    {
        // Jika pengguna adalah 'admin' atau 'kepala'
        return in_array($user->role, ['admin', 'kepala']) && $permissionRequest->status_permohonan === 'Menunggu Persetujuan';
    }

    public function reject(User $user, PermissionRequest $permissionRequest)
    {
        // Jika pengguna adalah 'admin' atau 'kepala' dan permohonan belum disetujui
        return in_array($user->role, ['admin', 'kepala']) && $permissionRequest->status_permohonan === 'Menunggu Persetujuan';
    }

    public function delete(User $user)
    {
        // Jika pengguna adalah 'admin' atau 'kepala' dan permohonan belum disetujui
        return in_array($user->role, ['admin', 'kepala']);
    }
}
