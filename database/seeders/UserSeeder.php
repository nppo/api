<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    private const MAX_USERS = 100;

    public function run(): void
    {
        $role = Role::create(['name' => 'editor']);
        $permission = Permission::create(['name' => 'projects.update']);
        $role->givePermissionTo($permission);

        User::factory()
            ->times(self::MAX_USERS)
            ->create()
            ->each(function ($user) use ($role): void {
                $user->assignRole($role);
            });
    }
}
