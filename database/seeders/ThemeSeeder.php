<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    private const MAX_THEMES = 15;

    public function run(): void
    {
        Theme::factory()
            ->count(self::MAX_THEMES)
            ->create();
    }
}
