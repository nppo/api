<?php

declare(strict_types=1);

namespace App\Import\Resolvers\Person;

use App\Import\Interfaces\CompositableResolver;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class UserEmailResolver implements CompositableResolver
{
    public function canResolve(array $data): bool
    {
        return Arr::has($data, 'email') && !empty(Arr::get($data, 'email'));
    }

    public function resolve(array $data): ?Person
    {
        $query = $this->usingEmail(Arr::get($data, 'email'));

        if ($query->count() === 1) {
            /** @var Person */
            $person = $query->sole();

            return $person;
        }

        return null;
    }

    private function usingEmail(string $email): Builder
    {
        return User::where('email', $email)->whereHas('person');
    }
}
