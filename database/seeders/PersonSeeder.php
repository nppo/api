<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Theme;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    private const MAX_THEMES = 3;

    public function run(): void
    {
        Person::factory()
            ->times(30)
            ->create()
            ->each(function (Person $person): void {
                $person
                    ->themes()
                    ->saveMany(
                        Theme::inRandomOrder()->limit(mt_rand(1, self::MAX_THEMES))
                    );
            });
    }
}
