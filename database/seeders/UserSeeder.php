<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const MAX_USERS = 100;

    public function run(): void
    {
        User::factory()
            ->times(self::MAX_USERS)
            ->create();
    }
}
