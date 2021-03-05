<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Repositories\ProjectRepository;

class ProjectController extends Controller
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function show($id): ProjectResource
    {
        return new ProjectResource(
            $this->projectRepository->show($id)
        );
    }

    public function update(ProjectUpdateRequest $request): ProjectResource
    {
        $this
            ->projectRepository
            ->update(
                $request->validated(),
                $request->getId()
            );

        return new ProjectResource(
            $this->projectRepository->show($request->getId())
        );
    }
}
