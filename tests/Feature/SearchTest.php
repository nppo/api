<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enumerators\Entities;
use App\Enumerators\Filters;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Project;
use App\Models\Theme;
use Illuminate\Support\Arr;
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
            ->getJson(
                route('api.search', [
                    'filters' => [Filters::THEMES => [$theme->id]],
                ])
            );

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
                    'filters' => [Filters::THEMES => [$themes->first()->id, $themes->get(1)->id]],
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
        Project::factory()->create();

        $response = $this->getJson(route('api.search'));

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data.products')
            ->assertJsonCount(1, 'data.parties')
            ->assertJsonCount(1, 'data.people')
            ->assertJsonCount(1, 'data.projects');
    }

    /** @test */
    public function it_can_filter_by_type(): void
    {
        Party::factory()->create();
        Person::factory()->create();
        Product::factory()->create();
        Project::factory()->create();

        $typeFilter = Entities::getByReferableValue(Entities::PRODUCT, 'id');

        $response = $this->getJson(
            route('api.search', [
                'filters' => [Filters::TYPES => [$typeFilter]],
            ])
        );

        dump($response->json());

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
        Project::factory()->create();

        $typeFilters = [
            $typeFilter = Entities::getByReferableValue(Entities::PRODUCT, 'id'),
            $typeFilter = Entities::getByReferableValue(Entities::PERSON, 'id'),
        ];

        $response = $this->getJson(
            route('api.search', [
                'filters' => [Filters::TYPES => $typeFilters],
            ])
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data.products')
            ->assertJsonCount(0, 'data.parties')
            ->assertJsonCount(1, 'data.people')
            ->assertJsonCount(0, 'data.projects');
    }

    /** @test */
    public function it_orders_products_by_published_at_desc_by_default(): void
    {
        Product::factory()
            ->count(3)
            ->create();

        $response = $this->getJson(
            route('api.search', [
                'filter' => ['types' => [Entities::getByReferableValue(Entities::PRODUCT, 'id')]],
            ])
        );

        $products = Product::orderBy('published_at', 'desc')
            ->get()
            ->pluck('published_at')
            ->toArray();

        $this->assertEquals($products, Arr::pluck($response['data']['products'], 'publishedAt'));
    }
}
