<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enumerators\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (Roles::asArray() as $role => $permissions) {
            $role = Role::findOrCreate($role);

            foreach ($permissions as $permission) {
                $role->givePermissionTo(Permission::findOrCreate($permission));
            }
        }

        User::all()
            ->each(function ($user) use ($role): void {
                $user->assignRole(Role::inRandomOrder()->first());
            });
    }
}
