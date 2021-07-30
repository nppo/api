<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enumerators\Permissions;
use App\Models\Theme;
use App\Models\User;

class ThemePolicy
{
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::THEME_CREATE);
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function update(User $user, Theme $theme): bool
    {
        return $user->hasPermissionTo(Permissions::THEME_UPDATE);
    }
}
