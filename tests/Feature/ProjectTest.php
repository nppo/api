<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enumerators\Permissions;
use App\Models\Party;
use App\Models\Person;
use App\Models\Project;
use App\Models\User;
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

        $user = User::factory()
            ->create()
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
        $user = User::factory()
            ->create([
                'person_id' => Person::factory(),
            ])
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
}
