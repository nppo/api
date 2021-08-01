<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enumerators\Permissions;
use App\Models\User;

class PartyPolicy
{
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::PARTY_CREATE);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::PARTY_UPDATE);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::PARTY_DELETE);
    }
}
