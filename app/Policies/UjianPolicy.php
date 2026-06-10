<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Ujian;
use App\Models\User;
use App\Policies\Concerns\HandlesGuruMapelAuthorization;

class UjianPolicy
{
    use HandlesGuruMapelAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Guru || $user->role === UserRole::Admin;
    }

    public function view(User $user, Ujian $ujian): bool
    {
        return $this->guruMengajarMapel($user, $ujian);
    }

    public function create(User $user): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->guru && $user->guru->mata_pelajaran;
    }

    public function update(User $user, Ujian $ujian): bool
    {
        return $this->guruMengajarMapel($user, $ujian);
    }

    public function delete(User $user, Ujian $ujian): bool
    {
        return $this->guruMengajarMapel($user, $ujian);
    }
}
