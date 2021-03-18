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

    public function create(User $user)
    {
        if (!$user->can(Permissions::PROJECTS_CREATE)) {
            return false;
        }

        return !is_null($user->person);
    }

    public function update(User $user, Project $project, $productIds = []): bool
    {
        if (!$user->can(Permissions::PROJECTS_UPDATE)) {
            return false;
        }

        if (!$user->person) {
            return false;
        }

        if (!$project->owner->contains($user->person)) {
            return false;
        }

        if ($productIds && $user->person->products->whereIn('id', $productIds)->count() !== count($productIds)) {
            return false;
        }

        return true;
    }
}
