<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enumerators\Permissions;
use App\Models\Tag;
use App\Models\User;

class TagPolicy
{
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::TAG_CREATE);
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function update(User $user, Tag $tag): bool
    {
        return $user->hasPermissionTo(Permissions::TAG_UPDATE);
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function delete(User $user, Tag $tag): bool
    {
        return $user->hasPermissionTo(Permissions::TAG_DELETE);
    }
}
