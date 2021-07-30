<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enumerators\Permissions;
use App\Models\Party;
use App\Models\User;

class PartyPolicy
{
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::PARTY_CREATE);
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function update(User $user, Party $party): bool
    {
        return $user->hasPermissionTo(Permissions::PARTY_UPDATE);
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function delete(User $user, Party $party): bool
    {
        return $user->hasPermissionTo(Permissions::PARTY_DELETE);
    }
}
