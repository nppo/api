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

    public function update(ProjectUpdateRequest $request, $id): ProjectResource
    {
        $this
            ->projectRepository
            ->update(
                $request->validated(),
                $id
            );

        return new ProjectResource(
            $this->projectRepository->show($id)
        );
    }
}
