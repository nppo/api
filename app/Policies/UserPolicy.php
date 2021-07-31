<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enumerators\Permissions;
use App\Models\User;

class UserPolicy
{
    public function viewAnyLike(User $authUser, User $user): bool
    {
        if (!$authUser->is($user)) {
            return false;
        }

        return true;
    }

    public function createLike(User $authUser, User $user): bool
    {
        if (!$authUser->is($user)) {
            return false;
        }

        return true;
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USER_UPDATE);
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::USER_DELETE);
    }
}
