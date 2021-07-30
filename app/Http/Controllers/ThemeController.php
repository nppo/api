<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Theme\StoreRequest;
use App\Http\Requests\Theme\UpdateRequest;
use App\Http\Resources\ThemeResource;
use App\Models\Theme;
use App\Repositories\ThemeRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Way2Web\Force\Http\Controller;

class ThemeController extends Controller
{
    private ThemeRepository $themeRepository;

    public function __construct(ThemeRepository $themeRepository)
    {
        $this->themeRepository = $themeRepository;

        $this
            ->protectActionRoutes(['api']);
    }

    public function index(): AnonymousResourceCollection
    {
        return ThemeResource::collection(
            $this->themeRepository->index()
        );
    }

    public function show(string $id): ThemeResource
    {
        return ThemeResource::make($this->themeRepository->show($id));
    }

    public function store(StoreRequest $storeRequest): ThemeResource
    {
        $this->authorize('create', Theme::class);

        $theme = $this->themeRepository->createFull($storeRequest->validated());

        return ThemeResource::make($theme);
    }

    public function update(string $id, UpdateRequest $updateRequest): ThemeResource
    {
        $theme = $this->themeRepository->findOrFail($id);

        $this->authorize('update', $theme);

        $theme = $this->themeRepository->updateFull($id, $updateRequest->validated());

        return ThemeResource::make($theme);
    }
}
