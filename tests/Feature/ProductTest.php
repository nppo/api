<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enumerators\Action;
use App\Enumerators\ProductTypes;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
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
        $this->withoutExceptionHandling();
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
                    'file'        => UploadedFile::fake()->create('test.jpg'),
                ]
            )
            ->assertOk();

        $this->assertEquals(
            $original + 1,
            Product::count()
        );
    }

    /** @test */
    public function it_can_create_a_product_to_have_children(): void
    {
        $this->withoutExceptionHandling();
        /** @var Product $product */
        $product = Product::factory()->make();

        $children = Product::factory()->times(2)->create();

        $user = $this->getUser();

        Passport::actingAs($user);

        $response = $this
            ->postJson(
                route('api.products.store'),
                array_merge(
                    $product->toArray(),
                    [
                        'children' => $children->map->only('id'),
                        'file'     => UploadedFile::fake()->create('test.jpg'),
                    ]
                )
            )
            ->assertOk();

        $this->assertCount(2, $response->json('data.children'));

        foreach ($children->pluck('id') as $key => $id) {
            $this->assertEquals($response->json('data.children')[$key]['id'], $id);
        }
    }

    /** @test */
    public function it_can_update_a_product_to_have_children(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $children = Product::factory()->times(2)->create();

        $user = $this->getUser();

        $product->people()->attach($user->person, ['is_owner' => false]);

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $product->title = $newTitle;

        $response = $this
            ->putJson(
                route('api.products.update', ['product' => $product->id]),
                array_merge($product->toArray(), ['children' => $children->map->only('id')])
            )
            ->assertOk();

        $this->assertCount(2, $response->json('data.children'));

        foreach ($children->pluck('id') as $key => $id) {
            $this->assertEquals($response->json('data.children')[$key]['id'], $id);
        }
    }

    /** @test */
    public function it_can_update_a_product_to_remove_children(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $children = Product::factory()->times(2)->create();

        $product->children()->saveMany($children);

        $user = $this->getUser();

        $product->people()->attach($user->person, ['is_owner' => false]);

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $product->title = $newTitle;

        $response = $this
            ->putJson(
                route('api.products.update', ['product' => $product->id]),
                array_merge($product->toArray(), ['children' => null])
            )
            ->assertOk();

        $this->assertCount(0, $response->json('data.children'));
        $this->assertCount(2, Product::findMany($children->pluck('id')));
    }

    /** @test */
    public function it_can_create_a_product_to_have_parents(): void
    {
        /** @var Product $product */
        $product = Product::factory()->make();

        $parents = Product::factory()->times(2)->create();

        $user = $this->getUser();

        Passport::actingAs($user);

        $response = $this
            ->postJson(
                route('api.products.store'),
                array_merge(
                    $product->toArray(),
                    [
                        'parents' => $parents->map->only('id'),
                        'file'    => UploadedFile::fake()->create('test.jpg'),
                    ]
                )
            )
            ->assertOk();

        $this->assertCount(2, $response->json('data.parents'));

        foreach ($parents->pluck('id') as $key => $id) {
            $this->assertEquals($response->json('data.parents')[$key]['id'], $id);
        }
    }

    /** @test */
    public function it_can_update_a_product_to_have_parents(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $parents = Product::factory()->times(2)->create();

        $user = $this->getUser();

        $product->people()->attach($user->person, ['is_owner' => false]);

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $product->title = $newTitle;

        $response = $this
            ->putJson(
                route('api.products.update', ['product' => $product->id]),
                array_merge($product->toArray(), ['parents' => $parents->map->only('id')])
            )
            ->assertOk();

        $this->assertCount(2, $response->json('data.parents'));

        foreach ($parents->pluck('id') as $key => $id) {
            $this->assertEquals($response->json('data.parents')[$key]['id'], $id);
        }
    }

    /** @test */
    public function it_can_update_a_product_to_remove_parents(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create([
            'parent_id' => Product::factory()->create(),
        ]);

        $user = $this->getUser();

        $product->people()->attach($user->person, ['is_owner' => false]);

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $product->title = $newTitle;

        $response = $this
            ->putJson(
                route('api.products.update', ['product' => $product->id]),
                array_merge($product->toArray(), ['parent' => null])
            )
            ->assertOk();

        $this->assertEquals($response->json('data.parent'), null);
    }

    /** @test */
    public function it_can_not_update_a_product_if_both_children_and_parents_are_filled(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $parents = Product::factory()->times(2)->create();

        $children = Product::factory()->times(2)->create();

        $user = $this->getUser();

        $product->people()->attach($user->person, ['is_owner' => false]);

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $product->title = $newTitle;

        $response = $this
            ->putJson(
                route('api.products.update', ['product' => $product->id]),
                array_merge(
                    $product->toArray(),
                    [
                        'parents'  => $parents->map->only('id'),
                        'children' => $children->map->only('id'),
                    ]
                )
            );

        $response->assertJsonValidationErrors([
            'parents.0.id'  => 'validation.prohibited_unless',
            'children.0.id' => 'validation.prohibited_unless',
        ]);
    }

    /** @test */
    public function it_can_not_create_a_product_if_both_children_and_parents_are_filled(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $parents = Product::factory()->times(2)->create();

        $children = Product::factory()->times(2)->create();

        $user = $this->getUser();

        $product->people()->attach($user->person, ['is_owner' => false]);

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $product->title = $newTitle;

        $response = $this
            ->postJson(
                route('api.products.store'),
                array_merge(
                    $product->toArray(),
                    [
                        'file'     => UploadedFile::fake()->create('test.jpg'),
                        'parents'  => $parents->map->only('id'),
                        'children' => $children->map->only('id'),
                    ]
                )
            );

        $response->assertJsonValidationErrors([
            'parents.0.id'  => 'validation.prohibited_unless',
            'children.0.id' => 'validation.prohibited_unless',
        ]);
    }
}
