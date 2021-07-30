<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enumerators\Permissions;
use App\Models\Person;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function create(User $user, array $productIds = []): bool
    {
        if (!$user->can(Permissions::PROJECTS_CREATE)) {
            return false;
        }

        if (!$user->person) {
            return false;
        }

        if ($productIds && $this->validateProducts($user->person, $productIds)) {
            return false;
        }

        return true;
    }

    public function update(User $user, Project $project, array $productIds = []): bool
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

        if ($productIds && $this->validateProducts($user->person, $productIds)) {
            return false;
        }

        return true;
    }

    protected function validateProducts(Person $person, array $productIds): bool
    {
        return $person->products->whereIn('id', $productIds)->count() !== count($productIds);
    }
}
