<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Collection;
use Way2Web\Force\Http\Controller;

class ProjectController extends Controller
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->protectActionRoutes(['api']);
        $this->projectRepository = $projectRepository;
    }

    public function show($id): ProjectResource
    {
        return ProjectResource::make(
            $this->projectRepository->show($id)
        )
            ->withPermissions();
    }

    public function store(ProjectStoreRequest $request): ProjectResource
    {
        $this->authorize('create', Project::class);

        $validated = $request->validated();

        /** @var Project */
        $project = $this
            ->projectRepository
            ->create($validated);

        if (isset($validated['people'])) {
            $project->people()->syncWithPivotValues(
                Collection::make($validated['people'])
                    ->map(fn ($person) => $person['id']),
                [
                    'is_owner' => false,
                ]
            );
        }

        $project->people()->attach($request->user()->person, ['is_owner' => true]);

        return ProjectResource::make(
            $this->projectRepository->show($project->getKey())
        );
    }

    public function update(ProjectUpdateRequest $request, $id): ProjectResource
    {
        $this->authorize('update', $this->projectRepository->findOrFail($id));

        $this
            ->projectRepository
            ->update(
                $request->validated(),
                $id
            );

        return ProjectResource::make(
            $this->projectRepository->show($id)
        )
            ->withPermissions();
    }
}
