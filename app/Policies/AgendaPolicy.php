<?php

namespace App\Policies;

use App\Models\Agenda;
use App\Models\User;

class AgendaPolicy
{
    public function edit(User $user, Agenda $agenda): bool
    {
        // Hanya izinkan admin yang dapat melakukan update
        return in_array($user->role, ['admin', 'kepala']);
    }

    public function delete(User $user, Agenda $agenda): bool
    {
        // Hanya izinkan admin yang dapat melakukan delete
        return in_array($user->role, ['admin', 'kepala']);
    }
}
