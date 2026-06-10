<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Tugas;
use App\Models\User;

class TugasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Guru || $user->role === UserRole::Admin;
    }

    public function view(User $user, Tugas $tugas): bool
    {
        $guru = $user->guru;

        return $guru && $tugas->guru_id === $guru->id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Guru;
    }

    public function update(User $user, Tugas $tugas): bool
    {
        $guru = $user->guru;

        return $guru && $tugas->guru_id === $guru->id;
    }

    public function delete(User $user, Tugas $tugas): bool
    {
        $guru = $user->guru;

        return $guru && $tugas->guru_id === $guru->id;
    }
}
