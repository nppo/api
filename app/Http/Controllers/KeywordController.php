<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Keyword\StoreRequest;
use App\Http\Requests\Keyword\UpdateRequest;
use App\Http\Resources\KeywordResource;
use App\Models\Keyword;
use App\Repositories\KeywordRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Way2Web\Force\Http\Controller;

class KeywordController extends Controller
{
    private KeywordRepository $keywordRepository;

    public function __construct(KeywordRepository $keywordRepository)
    {
        $this->keywordRepository = $keywordRepository;

        $this
            ->protectActionRoutes(['api']);
    }

    public function index(): AnonymousResourceCollection
    {
        return KeywordResource::collection(
            $this->keywordRepository->index()
        );
    }

    public function show(string $id): KeywordResource
    {
        return KeywordResource::make($this->keywordRepository->show($id));
    }

    public function store(StoreRequest $storeRequest): KeywordResource
    {
        $this->authorize('create', Keyword::class);

        $keyword = $this->keywordRepository->createFull($storeRequest->validated());

        return KeywordResource::make($keyword);
    }

    public function update(string $id, UpdateRequest $updateRequest): KeywordResource
    {
        $keyword = $this->keywordRepository->findOrFail($id);

        $this->authorize('update', $keyword);

        $keyword = $this->keywordRepository->updateFull($id, $updateRequest->validated());

        return KeywordResource::make($keyword);
    }

    public function destroy(string $id): KeywordResource
    {
        $keyword = $this->keywordRepository->findOrFail($id);

        $this->authorize('delete', $keyword);

        $keyword = $this->keywordRepository->deleteFull($id);

        return KeywordResource::make($keyword);
    }
}
