<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enumerators\ProductTypes;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class ProductContentSeeder extends Seeder
{
    public function run(): void
    {
        $collectionProducts = Product::where('type', ProductTypes::COLLECTION)->inRandomOrder()->get();

        $this->command->getOutput()->progressStart($collectionProducts->count());

        $collectionProducts->each(function ($product): void {
            /** @var Collection $products */
            $products = Product::where('type', '!=', ProductTypes::COLLECTION)
                ->whereHas('people', function ($query) use ($product): void {
                    $query->whereIn('id', $product->people->pluck('id'));
                })
                ->get();

            $this->command->getOutput()->progressAdvance();

            if ($product->children()->count() > 0 || $product->parents()->count() > 0) {
                return;
            }

            $product->children()->sync($products->random(mt_rand(1, $products->count())));
        });

        $this->command->getOutput()->progressFinish();
    }
}
