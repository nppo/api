<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enumerators\Disks;
use App\Enumerators\MediaCollections;
use App\Models\Party;
use App\Models\Person;
use App\Models\Tag;
use App\Models\Theme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PersonSeeder extends Seeder
{
    private const MAX_PEOPLE = 100;

    private const MAX_TAGS = 10;

    private const MAX_THEMES = 5;

    private const MAX_PARTIES = 2;

    private ?array $seedingOptions = null;

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

                if (rand(0, 1)) {
                    $this->attachProfilePicture($person);
                }
            });
    }

    private function attachParty(Person $person, Collection $parties): void
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

    private function attachProfilePicture(Person $person): void
    {
        $person
            ->addMediaFromDisk(
                Arr::random($this->getSeedingOptions()),
                Disks::SEEDING
            )
            ->usingFileName(Str::uuid()->toString())
            ->preservingOriginal()
            ->toMediaCollection(MediaCollections::PROFILE_PICTURE);
    }

    private function getSeedingOptions(): array
    {
        if (is_null($this->seedingOptions)) {
            $this->seedingOptions = Storage::disk(Disks::SEEDING)
                ->files('people');
        }

        return $this->seedingOptions;
    }
}
