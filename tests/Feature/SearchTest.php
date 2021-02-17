<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enumerators\Entities;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Theme;
use phpDocumentor\Reflection\Project;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /** @test */
    public function it_can_search_by_query(): void
    {
        $product = Product::factory()->create();

        $response = $this
            ->getJson(route('api.search', ['query' => $product->title]));

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
            ->getJson(route('api.search', ['filters' => ['theme' => [$theme->id]]]));

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
                route('api.search', [
                    'filters' => ['theme' => [$themes->first()->id, $themes->get(1)->id]],
                ])
            );

        $response
            ->assertOk()
            ->assertJsonFragment(['title' => $products->first()->title])
            ->assertJsonFragment(['title' => $products->get(1)->title])
            ->assertJsonMissing(['title' => $products->get(2)->title]);
    }

    /** @test */
    public function it_will_return_all_results_when_no_filters_are_set(): void
    {
        Party::factory()->create();
        Person::factory()->create();
        Product::factory()->create();
//        Project::factory()->create();

        $response = $this->getJson(route('api.search'));

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data.products')
            ->assertJsonCount(1, 'data.parties')
            ->assertJsonCount(1, 'data.people')
            ->assertJsonCount(0, 'data.projects');
    }

    /** @test */
    public function it_can_filter_by_type(): void
    {
        Party::factory()->create();
        Person::factory()->create();
        Product::factory()->create();
//        Project::factory()->create();

        $response = $this->getJson(
            route('api.search', [
                'filters' => ['type' => [Entities::PRODUCT]],
            ])
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data.products')
            ->assertJsonCount(0, 'data.parties')
            ->assertJsonCount(0, 'data.people')
            ->assertJsonCount(0, 'data.projects');
    }

    /** @test */
    public function it_can_filter_by_multiple_types(): void
    {
        Party::factory()->create();
        Person::factory()->create();
        Product::factory()->create();

        $response = $this->getJson(
            route('api.search', [
                'filters' => ['type' => [Entities::PRODUCT, Entities::PERSON]],
            ])
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data.products')
            ->assertJsonCount(0, 'data.parties')
            ->assertJsonCount(1, 'data.people')
            ->assertJsonCount(0, 'data.projects');
    }
}
