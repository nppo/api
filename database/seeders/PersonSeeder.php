<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Party;
use App\Models\Person;
use App\Models\Tag;
use App\Models\Theme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PersonSeeder extends Seeder
{
    private const MAX_PEOPLE = 100;

    private const MAX_TAGS = 10;

    private const MAX_THEMES = 5;

    private const MAX_PARTIES = 2;

    public function run(): void
    {
        $tags = Tag::all();
        $themes = Theme::all();
        $parties = Party::all();

        Person::factory()
            ->times(self::MAX_PEOPLE)
            ->create()
            ->each(function (Person $person) use ($parties, $tags, $themes): void {
                $this->attachTags($person, $tags);
                $this->attachThemes($person, $themes);
                $this->attachParty($person, $parties);
            });
    }

    private function attachParty(Person $person, Collection $parties)
    {
        $person
            ->parties()
            ->saveMany($parties->random((mt_rand(0, self::MAX_PARTIES))));
    }

    private function attachTags(Person $person, Collection $tags): void
    {
        $person
            ->tags()
            ->saveMany($tags->random(mt_rand(1, self::MAX_TAGS)));
    }

    private function attachThemes(Person $person, Collection $themes): void
    {
        $person
            ->themes()
            ->saveMany($themes->random(mt_rand(1, self::MAX_THEMES)));
    }
}
