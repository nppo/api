<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(StructureSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(ThemeSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(PartySeeder::class);
        $this->call(PersonSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductContentSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(PassportSeeder::class);
    }
}
