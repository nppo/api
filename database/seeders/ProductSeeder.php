<?php

declare(strict_types=1);

namespace Database\Seeders;

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

    public function run(): void
    {
        $this->command->getOutput()->progressStart(self::MAX_PRODUCTS);

        $themes = Theme::all();
        $tags = Tag::all();
        $people = Person::all();
        $users = User::all();

        Product::factory()
            ->times(self::MAX_PRODUCTS)
            ->create([
                'theme_id' => function () use ($themes): Theme {
                    return $themes->random(1)->first();
                },
            ])
            ->each(function (Product $product) use ($tags, $people, $users): void {
                $this->attachTags($product, $tags);
                $this->attachPeople($product, $people);
                $this->attachLikes($product, $users);

                $this->command->getOutput()->progressAdvance(1);
            });

        $this->command->getOutput()->progressFinish();
    }

    private function attachPeople(Product $product, Collection $people): void
    {
        $product
            ->people()
            ->sync(
                $people
                    ->random(mt_rand(0, $people->count()))
                    ->pluck('id')
                    ->toArray()
            );
    }

    private function attachTags(Product $product, Collection $tags): void
    {
        $product
            ->tags()
            ->sync(
                $tags->random(mt_rand(1, self::MAX_TAGS_PER_PRODUCT))
            );
    }

    private function attachLikes(Product $product, Collection $users): void
    {
        $product
            ->likes()
            ->sync(
                $users
                    ->random(mt_rand(0, $users->count()))
                    ->pluck('id')
                    ->toArray()
            );
    }
}
