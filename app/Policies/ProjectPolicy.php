<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enumerators\Permissions;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Project $project): bool
    {
        if (!$user->can(Permissions::PROJECTS_UPDATE)) {
            return false;
        }

        if (!$user->person) {
            return false;
        }

        if (!$project->owner()->where('id', $user->person->id)->exists()) {
            return false;
        }

        return true;
    }
}
