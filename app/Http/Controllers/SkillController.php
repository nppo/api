<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SkillResource;
use App\Repositories\SkillRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SkillController extends Controller
{
    private SkillRepository $skillRepository;

    public function __construct(SkillRepository $skillRepository)
    {
        $this->skillRepository = $skillRepository;
    }

    public function index(): AnonymousResourceCollection
    {
        return SkillResource::collection(
            $this->skillRepository->index()
        );
    }
}
