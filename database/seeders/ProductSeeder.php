<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enumerators\Disks;
use App\Enumerators\MediaCollections;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Theme;
use App\Models\User;
use Database\Seeders\Support\SeedsMedia;
use Database\Seeders\Support\SeedsMetadata;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ProductSeeder extends Seeder
{
    use SeedsMetadata, SeedsMedia;

    private const MAX_PRODUCTS = 250;

    private const MAX_TAGS_PER_PRODUCT = 10;

    private const MAX_PEOPLE = 10;

    private const MAX_THEMES = 3;

    private const MAX_PARTIES = 3;

    public function run(): void
    {
        $this->command->getOutput()->progressStart(self::MAX_PRODUCTS);

        $themes = Theme::all();
        $tags = Tag::all();
        $people = Person::all();
        $parties = Party::all();
        $users = User::all();

        Product::factory()
            ->times(self::MAX_PRODUCTS)
            ->create()
            ->each(function (Product $product) use ($themes, $tags, $people, $parties, $users): void {
                $this->attachThemes($product, $themes);
                $this->attachTags($product, $tags);
                $this->attachPeople($product, $people);
                $this->attachParties($product, $parties);
                $this->attachLikes($product, $users);
                $this->seedMedia($product);
                $this->seedMetadata($product);

                $this->command->getOutput()->progressAdvance(1);
            });

        $this->command->getOutput()->progressFinish();
    }

    private function attachThemes(Product $product, Collection $themes): void
    {
        $product
            ->themes()
            ->saveMany($themes->random(mt_rand(1, self::MAX_THEMES)));
    }

    /**
     * @param Product    $product
     * @param Collection $people
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function attachPeople(Product $product, Collection $people): void
    {
        $pivotAttributes = [];

        // Women and children first
        $peopleToSave = $people
            ->random(mt_rand(1, self::MAX_PEOPLE))
            ->each(function (Person $person, $key) use (&$pivotAttributes): void {
                $pivotAttributes[] = ['is_owner' => ($key === 0)];
            });

        $product
            ->people()
            ->saveMany($peopleToSave, $pivotAttributes);
    }

    private function attachParties(Product $product, Collection $parties): void
    {
        $pivotAttributes = [];

        $partiesToSave = $parties
            ->random(mt_rand(1, self::MAX_PARTIES))
            ->each(function () use (&$pivotAttributes): void {
                $pivotAttributes[] = ['is_owner' => false];
            });

        $product
            ->parties()
            ->saveMany($partiesToSave, $pivotAttributes);
    }

    private function attachTags(Product $product, Collection $tags): void
    {
        $product
            ->tags()
            ->saveMany(
                $tags->random(mt_rand(0, self::MAX_TAGS_PER_PRODUCT))
            );
    }

    private function attachLikes(Product $product, Collection $users): void
    {
        $product
            ->likes()
            ->saveMany(
                $users->random(mt_rand(0, $users->count()))
            );
    }

    private function seedMedia(Product $product): void
    {
        $file = $this->getRandomMediaFile($product->type);

        $product
            ->addMediaFromDisk($file, Disks::SEEDING)
            ->preservingOriginal()
            ->toMediaCollection(MediaCollections::PRODUCT_OBJECT);
    }
}
