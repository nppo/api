<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Tag\StoreRequest;
use App\Http\Requests\Tag\UpdateRequest;
use App\Http\Resources\TagResource;
use App\Repositories\TagRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Way2Web\Force\Http\Controller;

class TagController extends Controller
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;

        $this
            ->protectActionRoutes(['api']);
    }

    public function index(): AnonymousResourceCollection
    {
        return TagResource::collection(
            $this->tagRepository->index()
        );
    }

    public function show(string $id)
    {
        return TagResource::make($this->tagRepository->show($id));
    }

    public function store(StoreRequest $storeRequest)
    {
        $theme = $this->tagRepository->createFull($storeRequest->validated());

        return TagResource::make($theme);
    }

    public function update(string $id, UpdateRequest $updateRequest)
    {
        $theme = $this->tagRepository->updateFull($id, $updateRequest->validated());

        return TagResource::make($theme);
    }
}
