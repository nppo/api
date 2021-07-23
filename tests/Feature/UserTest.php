<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Project;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function it_can_like_a_product(): void
    {
        $this->withoutExceptionHandling();
        $user = $this->getUser();

        Passport::actingAs($user);

        $product = Product::factory()->create();

        $this
            ->postJson(
                route('api.users.likes.store', [$user->id]),
                [
                    'likable_type' => Product::class,
                    'likable_id'   => $product->getKey(),
                ]
            )
            ->assertOk();

        $this->assertTrue($user->likedProducts->contains($product));
    }

    /** @test */
    public function it_can_like_a_project(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $project = Project::factory()->create();

        $this
            ->postJson(
                route('api.users.likes.store', [$user->id]),
                [
                    'likable_type' => Project::class,
                    'likable_id'   => $project->getKey(),
                ]
            )
            ->assertOk();

        $this->assertTrue($user->likedProjects->contains($project));
    }

    /** @test */
    public function it_can_like_a_party(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $party = Party::factory()->create();

        $this
            ->postJson(
                route('api.users.likes.store', [$user->id]),
                [
                    'likable_type' => Party::class,
                    'likable_id'   => $party->getKey(),
                ]
            )
            ->assertOk();

        $this->assertTrue($user->likedParties->contains($party));
    }

    /** @test */
    public function it_can_like_a_person(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $person = Person::factory()->create();

        $this
            ->postJson(
                route('api.users.likes.store', [$user->id]),
                [
                    'likable_type' => Person::class,
                    'likable_id'   => $person->getKey(),
                ]
            )
            ->assertOk();

        $this->assertTrue($user->likedPeople->contains($person));
    }

    /** @test */
    public function it_can_view_all_liked_entities(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $user->likedProducts()->attach(Product::factory()->create());
        $user->likedProjects()->attach(Project::factory()->create());
        $user->likedParties()->attach(Party::factory()->create());
        $user->likedPeople()->attach(Person::factory()->create());

        $response = $this
            ->getJson(
                route('api.users.likes.index', [$user->id])
            )
            ->assertOk();

        $this->assertCount(1, $response->json('data.likedProducts'));
        $this->assertCount(1, $response->json('data.likedProjects'));
        $this->assertCount(1, $response->json('data.likedPeople'));
        $this->assertCount(1, $response->json('data.likedParties'));
    }
}
