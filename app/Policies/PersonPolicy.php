<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enumerators\Permissions;
use App\Models\Person;
use App\Models\User;

class PersonPolicy
{
    public function create(User $user): bool
    {
        if (!$user->can(Permissions::PEOPLE_CREATE)) {
            return false;
        }

        return is_null($user->person);
    }

    public function update(User $user, Person $person): bool
    {
        if (!$user->can(Permissions::PEOPLE_UPDATE)) {
            return false;
        }

        return $user->person->is($person);
    }
}
