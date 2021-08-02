<?php

declare(strict_types=1);

namespace App\Policies;

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
}
