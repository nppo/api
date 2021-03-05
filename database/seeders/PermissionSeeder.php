<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::create(['name' => 'editor']);
        $permission = Permission::create(['name' => 'projects.update']);
        $role->givePermissionTo($permission);

        User::all()
            ->each(function ($user) use ($role): void {
                $user->assignRole($role);
            });
    }
}
