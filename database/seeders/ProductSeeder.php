<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ProductSeeder extends Seeder
{
    private const MAX_PRODUCTS = 250;

    private const MAX_TAGS_PER_PRODUCT = 10;

    private const MAX_CONTRIBUTORS = 10;

    private const MAX_THEMES = 10;

    private const MAX_PARTIES = 2;

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
                $this->attachContributors($product, $people);
                $this->attachParties($product, $parties);
                $this->attachLikes($product, $users);

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

    private function attachContributors(Product $product, Collection $people): void
    {
        $product
            ->contributors()
            ->saveMany(
                $people->random(mt_rand(1, self::MAX_CONTRIBUTORS))
            );
    }

    private function attachParties(Product $product, Collection $parties): void
    {
        $product
            ->parties()
            ->saveMany(
                $parties->random(mt_rand(1, self::MAX_PARTIES))
            );
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
}
