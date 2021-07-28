<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use InvalidArgumentException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected function getUser(array $roles = ['RESEARCHER']): User
    {
        /** @var User */
        $user = User::factory()->create();

        foreach ($roles as $role) {
            $query = Role::where('name', $role);

            if (!$query->exists()) {
                throw new InvalidArgumentException('Specified role does not exist in DB');
            }

            $user->roles()->attach($query->sole());
        }

        $user->person()->associate(Person::factory()->create());

        $user->save();

        $this->clearCache();

        return $user;
    }

    protected function clearCache(): void
    {
        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
