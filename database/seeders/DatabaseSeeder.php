<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(PersonSeeder::class);
        $this->call(PartySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PassportSeeder::class);
    }
}
