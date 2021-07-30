<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enumerators\Disks;
use App\Enumerators\MediaCollections;
use App\Models\Party;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartySeeder extends Seeder
{
    private const MAX_PARTIES = 30;

    private const MAX_AFFILIABLE_PARTIES = 2;

    private ?array $seedingOptions = null;

    public function run(): void
    {
        $parties = Party::factory()
            ->times(self::MAX_PARTIES)
            ->create();

        $parties->each(function (Model $party) use ($parties): void {
            /** @var Party $party */
            $party
                ->parties()
                ->saveMany(
                    $parties
                        ->filter(function (Party $p) use ($party) {
                            return $p->id !== $party->id;
                        })
                        ->random(mt_rand(0, self::MAX_AFFILIABLE_PARTIES))
                );

            if (rand(0, 1)) {
                $this->attachPartyPicture($party);
            }
        });
    }

    private function attachPartyPicture(Party $party): void
    {
        $randomImage = Arr::random($this->getSeedingOptions());

        $party
            ->addMediaFromDisk(
                $randomImage,
                Disks::SEEDING
            )
            ->usingFileName(Str::uuid()->toString() . '.' . Str::afterLast($randomImage, '.'))
            ->preservingOriginal()
            ->toMediaCollection(MediaCollections::PARTY_PICTURE);
    }

    private function getSeedingOptions(): array
    {
        if (is_null($this->seedingOptions)) {
            $this->seedingOptions = Storage::disk(Disks::SEEDING)
                ->files('parties');
        }

        return $this->seedingOptions;
    }
}
