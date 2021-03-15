<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Person;
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

    public function store(ProjectRequest $request, Person $person)
    {
        $project = Project::create($request->all());

        $project->people()->sync(
            Collection::make($request->id)
        );

        $project->people()->update(['is_owner' => true]);

        $project->save();
    }

    public function update(ProjectRequest $request, $id): ProjectResource
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
