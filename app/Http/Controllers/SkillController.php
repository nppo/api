<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\TagTypes;
use App\Http\Resources\TagResource;
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
        return TagResource::collection(
            $this->skillRepository
                ->makeQuery()
                ->where('type', TagTypes::SKILL)
                ->orderBy('label')
                ->get()
        );
    }
}
