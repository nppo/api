<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Enumerators\Roles as RolesEnum;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class Roles
{
    public function bootstrap(): void
    {
        App::make(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (RolesEnum::asArray() as $role => $permissions) {
            $role = Role::updateOrCreate(['name' => $role]);

            foreach ($permissions as $permission) {
                $role->givePermissionTo(
                    Permission::where('name', $permission)->sole()
                );
            }
        }
    }
}
