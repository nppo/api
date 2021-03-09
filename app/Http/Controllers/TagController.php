<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Repositories\TagRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function index(): AnonymousResourceCollection
    {
        return TagResource::collection(
            $this->tagRepository
                ->makeQuery()
                ->orderBy('label')
                ->get()
        );
    }
}
