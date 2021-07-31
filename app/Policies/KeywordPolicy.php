<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enumerators\Permissions;
use App\Models\User;

class KeywordPolicy
{
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::KEYWORD_CREATE);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::KEYWORD_UPDATE);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::KEYWORD_DELETE);
    }
}
