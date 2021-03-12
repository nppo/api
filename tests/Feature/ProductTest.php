<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enumerators\Action;
use App\Models\Product;
use Laravel\Passport\Passport;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /** @test */
    public function it_can_not_update_a_product_with_an_guest_user(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $newTitle = '::new title::';

        $product->title = $newTitle;

        $this
            ->putJson(
                route('api.products.update', ['product' => $product->id]),
                $product->toArray()
            )
            ->assertUnauthorized();
    }

    /** @test */
    public function it_can_not_update_a_product_with_an_logged_in_user_without_permission(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $user = $this->getUser();

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $product->title = $newTitle;

        $this
            ->putJson(
                route('api.products.update', ['product' => $product->id]),
                $product->toArray()
            )
            ->assertForbidden();
    }

    /** @test */
    public function it_can_not_update_a_product_with_an_logged_in_user_with_permission_but_not_owner(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $user = $this->getUser();

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $product->title = $newTitle;

        $this
            ->putJson(
                route('api.products.update', ['product' => $product->id]),
                $product->toArray()
            )
            ->assertForbidden();
    }

    /** @test */
    public function it_can_update_a_product_with_an_logged_in_user_with_permission_and_is_owner(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $user = $this->getUser();

        $product->contributors()->attach($user->person);

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $product->title = $newTitle;

        $this
            ->putJson(
                route('api.products.update', ['product' => $product->id]),
                $product->toArray()
            )
            ->assertOk()
            ->assertJsonFragment(['title' => $newTitle]);
    }

    /** @test */
    public function it_can_see_can_permission_on_allowed_user(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $user = $this->getUser();

        $product->contributors()->attach($user->person);

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $product->title = $newTitle;

        $this
            ->getJson(
                route('api.products.show', ['product' => $product->id])
            )
            ->assertOk()
            ->assertJsonFragment(['can' => [
                Action::UPDATE => true,
            ]]);
    }
}
