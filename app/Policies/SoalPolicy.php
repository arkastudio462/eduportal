<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Soal;
use App\Models\User;
use App\Policies\Concerns\HandlesGuruMapelAuthorization;

class SoalPolicy
{
    use HandlesGuruMapelAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Guru || $user->role === UserRole::Admin;
    }

    public function view(User $user, Soal $soal): bool
    {
        return $this->guruMengajarMapel($user, $soal);
    }

    public function create(User $user): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->guru && $user->guru->mata_pelajaran;
    }

    public function update(User $user, Soal $soal): bool
    {
        return $this->guruMengajarMapel($user, $soal);
    }

    public function delete(User $user, Soal $soal): bool
    {
        return $this->guruMengajarMapel($user, $soal);
    }
}
