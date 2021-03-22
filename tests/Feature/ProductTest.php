<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enumerators\Action;
use App\Enumerators\ProductTypes;
use App\Models\Product;
use App\Models\User;
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
        $user = User::factory()->create();

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

        $product->people()->attach($user->person, ['is_owner' => false]);

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

        $product->people()->attach($user->person, ['is_owner' => false]);

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

    /** @test */
    public function a_new_product_can_be_created(): void
    {
        Passport::actingAs($this->getUser());

        $original = Product::count();

        $this
            ->postJson(
                route('api.products.store'),
                [
                    'type'        => ProductTypes::IMAGE,
                    'title'       => '::STRING::',
                    'description' => '::TEXT::',
                    'summary'     => '::TEXT::',
                ]
            )
            ->assertOk();

        $this->assertEquals(
            $original + 1,
            Product::count()
        );
    }

    /** @test */
    public function it_can_create_a_product(): void
    {
        Passport::actingAs($this->getUser());

        /** @var Product $product */
        $product = Product::factory()->make();

        $response = $this->postJson(
            route('api.products.store'),
            array_merge(
                $product->toArray(),
                ['published_at' => $product->published_at->toDatetimeString()],
            )
        )->assertOk();

        $response->assertJsonFragment([
            'title'       => $product->title,
            'type'        => $product->type,
            'summary'     => $product->summary,
            'description' => $product->description,
            'publishedAt' => $product->published_at->toJSON(),
        ]);
    }

    /** @test */
    public function it_can_update_a_product(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        /** @var Product $product */
        $product = Product::factory()->create();

        $product->people()->attach($user->person, ['is_owner' => false]);

        $product->title = '::TITLE::';
        $product->published_at = now();

        $response = $this->putJson(
            route('api.products.update', $product->id),
            array_merge(
                $product->toArray(),
                ['published_at' => $product->published_at->toDatetimeString()],
            )
        )->assertOk();

        $response->assertJsonFragment([
            'title'       => $product->title,
            'type'        => $product->type,
            'summary'     => $product->summary,
            'description' => $product->description,
            'publishedAt' => $product->published_at->toJSON(),
        ]);
    }
}
