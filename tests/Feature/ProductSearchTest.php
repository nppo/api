<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Theme;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    /** @test */
    public function it_can_search_by_query(): void
    {
        $product = Product::factory()->create();

        $response = $this
            ->getJson(route('api.products.search', ['query' => $product->title]));

        $response
            ->assertOk()
            ->assertJsonFragment(['title' => $product->title]);
    }

    /** @test */
    public function it_can_filter_by_theme(): void
    {
        $products = Product::factory()
            ->count(2)
            ->create();

        $theme = Theme::factory()->create();

        $products
            ->first()
            ->themes()
            ->save($theme);

        $response = $this
            ->getJson(route('api.products.search', ['filters' => ['themes' => [$theme->id]]]));

        $response
            ->assertOk()
            ->assertJsonFragment(['title' => $products->first()->title])
            ->assertJsonMissing(['title' => $products->last()->title]);
    }

    /** @test */
    public function it_can_filter_by_multiple_themes(): void
    {
        $products = Product::factory()
            ->count(3)
            ->create();

        $products->each(function (Product $product): void {
            $product
                ->themes()
                ->save(Theme::factory()->create());
        });

        $themes = Theme::all();

        $response = $this
            ->getJson(
                route('api.products.search', [
                    'filters' => ['themes' => [$themes->first()->id, $themes->get(1)->id]],
                ])
            );

        $response
            ->assertOk()
            ->assertJsonFragment(['title' => $products->first()->title])
            ->assertJsonFragment(['title' => $products->get(1)->title])
            ->assertJsonMissing(['title' => $products->get(2)->title]);
    }
}
