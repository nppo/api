<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enumerators\Permissions;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /** @test */
    public function it_can_not_update_a_project_with_an_guest_user(): void
    {
        /** @var Project $project */
        $project = Project::factory()->create();

        $person = Person::factory()->create();

        $project->people()->save($person, ['is_owner' => 1]);

        $user = User::factory()->create([
            'person_id' => $person->id,
        ]);

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $project->title = $newTitle;

        $this
            ->putJson(
                route('api.projects.update', ['project' => $project->id]),
                $project->toArray()
            )
            ->assertForbidden();
    }

    /** @test */
    public function it_can_not_update_a_project_with_an_logged_in_user_without_permission(): void
    {
        /** @var Project $project */
        $project = Project::factory()->create();
        $user = User::factory()->create();

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $project->title = $newTitle;

        $this
            ->putJson(
                route('api.projects.update', ['project' => $project->id]),
                $project->toArray()
            )
            ->assertForbidden();
    }

    /** @test */
    public function it_can_not_update_a_project_with_an_logged_in_user_with_permission_but_not_owner(): void
    {
        /** @var Project $project */
        $project = Project::factory()->create();

        /** @var User */
        $user = User::factory()
            ->create();

        $user
            ->givePermissionTo(
                Permission::findOrCreate(Permissions::PROJECTS_UPDATE)
            );

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $project->title = $newTitle;

        $this
            ->putJson(
                route('api.projects.update', ['project' => $project->id]),
                $project->toArray()
            )
            ->assertForbidden();
    }

    /** @test */
    public function it_can_update_a_project_with_an_logged_in_user_with_permission_and_is_owner(): void
    {
        /** @var Project $project */
        $project = Project::factory()->create();

        /** @var User $user */
        $user = User::factory()
            ->create([
                'person_id' => Person::factory(),
            ]);

        $user
            ->givePermissionTo(
                Permission::findOrCreate(Permissions::PROJECTS_UPDATE)
            );

        $project->people()->attach($user->person, ['is_owner' => true]);

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $project->title = $newTitle;

        $this
            ->putJson(
                route('api.projects.update', ['project' => $project->id]),
                $project->toArray()
            )
            ->assertOk()
            ->assertJsonFragment(['title' => $newTitle]);
    }

    /** @test */
    public function updating_will_associate_media_with_the_project(): void
    {
        $user = $this->getUser();

        /** @var Project */
        $project = Project::factory()->create();
        $project->people()->syncWithPivotValues($user->person, ['is_owner' => true]);

        $this->assertEmpty($project->media);

        Passport::actingAs($user);

        $this
            ->putJson(
                route('api.projects.update', [$project->id]),
                [
                    'project_picture' => UploadedFile::fake()->image('avatar.jpg', 1440, 350),
                ]
            )

            ->assertOk();

        $this->assertNotEmpty($project->media()->get());
    }

    /** @test */
    public function it_can_update_a_project_and_remove_all_parties(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        /** @var Project $project */
        $project = Project::factory()->hasAttached($user->person, ['is_owner' => true])->create();

        $project->parties()->sync(
            Party::factory()->times(7)->create()->pluck('id')
        );

        $this
            ->putJson(
                route('api.projects.update', [$project->id]),
                ['parties' => null]
            )
            ->assertOk()
            ->assertJsonFragment([
                'parties' => [],
            ]);
    }

    /** @test */
    public function it_can_update_a_project_and_add_parties(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        /** @var Project $project */
        $project = Project::factory()
            ->hasAttached($user->person, ['is_owner' => true])
            ->create();

        $parties = Party::factory()
            ->times(7)
            ->create()
            ->map->only(['id', 'name', 'description']);

        $response = $this
            ->putJson(
                route('api.projects.update', [$project->id]),
                ['parties' => $parties]
            )
            ->assertOk()
            ->assertJsonCount(7, 'data.parties');

        foreach ($parties as $party) {
            $response->assertJsonFragment($party);
        }
    }

    /** @test */
    public function it_can_update_a_project_and_remove_all_products(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        /** @var Project $project */
        $project = Project::factory()->hasAttached($user->person, ['is_owner' => true])->create();

        $project->products()->sync(
            Product::factory()->times(7)->create()->pluck('id')
        );

        $this
            ->putJson(
                route('api.projects.update', [$project->id]),
                ['products' => null]
            )
            ->assertOk()
            ->assertJsonFragment([
                'products' => [],
            ]);
    }

    /** @test */
    public function it_can_update_a_project_and_add_products(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        /** @var Project $project */
        $project = Project::factory()
            ->hasAttached($user->person, ['is_owner' => true])
            ->create();

        $products = Product::factory()
            ->times(7)
            ->create();

        $user->person->products()->attach($products);

        $products = $products->map->only(['id', 'title']);

        $response = $this
            ->putJson(
                route('api.projects.update', [$project->id]),
                ['products' => $products]
            )
            ->assertOk()
            ->assertJsonCount(7, 'data.products');

        foreach ($products as $product) {
            $response->assertJsonFragment($product);
        }
    }

    /** @test */
    public function it_can_not_update_a_project_and_add_a_product_where_the_user_has_not_contributed_to(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        /** @var Project $project */
        $project = Project::factory()
            ->hasAttached($user->person, ['is_owner' => true])
            ->create();

        $products = Product::factory()
            ->times(7)
            ->create()
            ->map->only(['id', 'title']);

        $this
            ->putJson(
                route('api.projects.update', [$project->id]),
                ['products' => $products]
            )
            ->assertForbidden();
    }

    /** @test */
    public function it_can_create_a_project_and_add_parties(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $parties = Party::factory()
            ->times(7)
            ->create()
            ->map->only(['id', 'name', 'description']);

        $project = Project::factory()
            ->make([
                'parties' => $parties,
            ])
            ->toArray();

        $response = $this
            ->postJson(
                route('api.projects.store'),
                $project
            )
            ->assertOk()
            ->assertJsonCount(7, 'data.parties');

        foreach ($parties as $party) {
            $response->assertJsonFragment($party);
        }
    }

    /** @test */
    public function it_can_not_create_a_project_and_add_a_product_where_the_user_has_not_contributed_to(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $project = Project::factory()
            ->make([
                'products' => Product::factory()
                    ->times(7)
                    ->create()
                    ->map->only(['id', 'title']),
            ])
            ->toArray();

        $this
            ->postJson(
                route('api.projects.store'),
                $project
            )
            ->assertForbidden();
    }

    /** @test */
    public function it_can_create_a_project_and_add_products(): void
    {
        $user = $this->getUser();

        Passport::actingAs($user);

        $products = Product::factory()
            ->times(7)
            ->create();

        $user->person->products()->attach($products);

        $products = $products->map->only(['id', 'title']);

        $project = Project::factory()
            ->make([
                'products' => $products,
            ])
            ->toArray();

        $response = $this
            ->postJson(
                route('api.projects.store'),
                $project
            )
            ->assertOk()
            ->assertJsonCount(7, 'data.products');

        foreach ($products as $product) {
            $response->assertJsonFragment($product);
        }
    }

    /** @test */
    public function it_can_not_create_a_project_with_an_guest_user(): void
    {
        /** @var Project $project */
        $project = Project::factory()->make();

        $person = Person::factory()->create();

        $user = User::factory()->create([
            'person_id' => $person->id,
        ]);

        Passport::actingAs($user);

        $this
            ->postJson(
                route('api.projects.store'),
                $project->toArray()
            )
            ->assertForbidden();
    }

    /** @test */
    public function it_can_not_create_a_project_with_an_logged_in_user_without_permission(): void
    {
        /** @var Project $project */
        $project = Project::factory()->create();
        $user = User::factory()->create();

        Passport::actingAs($user);

        $newTitle = '::new title::';

        $project->title = $newTitle;

        $this
            ->putJson(
                route('api.projects.update', ['project' => $project->id]),
                $project->toArray()
            )
            ->assertForbidden();
    }
}
