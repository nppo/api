<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $authUser, User $user)
    {
        if (!$authUser->is($user)) {
            return false;
        }

        return true;
    }

    public function create(User $authUser, User $user)
    {
        if (!$authUser->is($user)) {
            return false;
        }

        return true;
    }
}
