<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PersonSeeder extends Seeder
{
    private const MAX_TAGS = 5;

    public function run(): void
    {
        $tags = Tag::all();

        Person::factory()
            ->times(30)
            ->create()
            ->each(function (Person $person) use ($tags): void {
                $this->attachTags($person, $tags);
            });
    }

    private function attachTags(Person $person, Collection $tags): void
    {
        $person
            ->tags()
            ->saveMany($tags->random(mt_rand(1, self::MAX_TAGS)));
    }
}
