<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Theme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PersonSeeder extends Seeder
{
    private const MAX_THEMES = 3;

    public function run(): void
    {
        $themes = Theme::all();

        Person::factory()
            ->times(30)
            ->create()
            ->each(function (Person $person) use ($themes): void {
                $this->attachThemes($person, $themes);
            });
    }

    private function attachThemes(Person $person, Collection $themes) {
        $person
            ->themes()
            ->saveMany($themes->random(mt_rand(1, self::MAX_THEMES)));
    }
}
