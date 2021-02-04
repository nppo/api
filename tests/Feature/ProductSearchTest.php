<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    /** @test */
    public function it_can_search_by_query()
    {
        $product = Product::factory()->create();

        $response = $this
            ->getJson(route('api.products.search', ['query' => $product->title]));

        $response
            ->assertOk()
            ->assertJsonFragment(['title' => $product->title]);
    }
}
