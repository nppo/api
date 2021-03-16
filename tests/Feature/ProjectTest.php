<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enumerators\Permissions;
use App\Models\Person;
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
    public function updating_will_associate_media_with_the_project(): void
    {
        $project = Project::factory()->create();

        $this->assertEmpty($project->media);

        $this

            ->putJson(
                route('api.projects.update', [$project->id]),
                [
                    'profile_picture' => UploadedFile::fake()->image('avatar.jpg', 1440, 350),
                ]
            )

            ->assertOk();

        $this->assertNotEmpty($project->media()->get());
    }
}
