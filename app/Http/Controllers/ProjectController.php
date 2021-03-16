<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Arr;
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

    public function update(ProjectUpdateRequest $request, $id): ProjectResource
    {
        $project = $this->projectRepository->findOrFail($id);

        $this->authorize('update', $project);

        $validated = $request->validated();

        $this
            ->projectRepository
            ->update(
                Arr::except($request->validated(), ['parties']),
                $id
            );

        $this->syncRelation($project, 'parties', Arr::get($validated, 'parties') ?: []);

        return ProjectResource::make(
            $this->projectRepository->show($id)
        )
            ->withPermissions();
    }
}
