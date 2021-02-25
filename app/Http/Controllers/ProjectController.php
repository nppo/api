<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function index(): AnonymousResourceCollection
    {
        return ProjectResource::collection(
            $this->projectRepository->index()
        );
    }
}
