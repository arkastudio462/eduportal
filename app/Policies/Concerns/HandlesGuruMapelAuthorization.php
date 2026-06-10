<?php

namespace App\Policies\Concerns;

use App\Models\Mapel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait HandlesGuruMapelAuthorization
{
    protected function guruMengajarMapel(?User $user, Model $resource): bool
    {
        $guru = $user?->guru;
        if (! $guru || ! $guru->mata_pelajaran) {
            return false;
        }

        $mapel = Mapel::where('nama', $guru->mata_pelajaran)->first();

        return $mapel && $resource->mapel_id === $mapel->id;
    }
}
