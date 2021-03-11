<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all();

        User::all()
            ->each(function ($user) use ($roles): void {
                $user->assignRole($roles->shuffle()->first());
            });

        App::make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
