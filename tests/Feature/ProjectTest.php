<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enumerators\Permissions;
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

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /** @test */
    public function it_can_not_update_a_project_with_an_guest_user(): void
    {
        /** @var Project $project */
        $project = Project::factory()->create();

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
    public function it_can_update_a_project_with_an_logged_in_user_with_permission(): void
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
            ->assertOk()
            ->assertJsonFragment(['title' => $newTitle]);
    }
}
