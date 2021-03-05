<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Project;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /** @test */
    public function it_can_update_a_project(): void
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
            ->assertOk()
            ->assertJsonFragment(['title' => $newTitle]);
    }
}
