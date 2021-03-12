<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enumerators\Permissions;
use App\Models\Person;
use App\Models\User;

class PersonPolicy
{
    public function update(User $user, Person $person)
    {
        if (!$user->can(Permissions::PEOPLE_UPDATE)) {
            return false;
        }

        return $user->person->is($person);
    }
}
