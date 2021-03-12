<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Enumerators\Permissions as PermissionsEnum;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class Permissions
{
    public function bootstrap(): void
    {
        App::make(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (PermissionsEnum::asArray() as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }
    }
}
