<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enumerators\Disks;
use App\Enumerators\MediaCollections;
use App\Models\Keyword;
use App\Models\Party;
use App\Models\Person;
use App\Models\User;
use Database\Seeders\Support\SeedsMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PersonSeeder extends Seeder
{
    use SeedsMetadata;

    private const MAX_PEOPLE = 50;

    private const MAX_KEYWORDS = 10;

    private const MAX_PARTIES = 2;

    private ?array $seedingOptions = null;

    public function run(): void
    {
        $keywords = Keyword::all();
        $parties = Party::all();
        $users = User::all();

        Person::factory()
            ->times(self::MAX_PEOPLE)
            ->create()
            ->each(function (Model $person) use ($parties, $keywords, $users): void {
                // @var Person $person
                $this->attachKeywords($person, $keywords);
                $this->attachParty($person, $parties);

                if (rand(0, 1)) {
                    $this->attachProfilePicture($person);
                }

                $this->attachUser($person, $users);
                $this->seedMetadata($person);
            });
    }

    private function attachParty(Person $person, Collection $parties): void
    {
        $person
            ->parties()
            ->saveMany($parties->random((mt_rand(0, self::MAX_PARTIES))));
    }

    private function attachKeywords(Person $person, Collection $keywords): void
    {
        $person
            ->keywords()
            ->saveMany($keywords->random(mt_rand(1, self::MAX_KEYWORDS)));
    }

    private function attachProfilePicture(Person $person): void
    {
        $randomImage = Arr::random($this->getSeedingOptions());

        $person
            ->addMediaFromDisk(
                $randomImage,
                Disks::SEEDING
            )
            ->usingFileName(Str::uuid()->toString() . '.' . Str::afterLast($randomImage, '.'))
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

    private function attachUser(Person $person, Collection $users): void
    {
        if ($users->count() === 0) {
            return;
        }

        $person
            ->user()
            ->save($users->shift());
    }
}
