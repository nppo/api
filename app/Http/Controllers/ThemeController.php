<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ThemeResource;
use App\Repositories\ThemeRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ThemeController extends Controller
{
    private ThemeRepository $themeRepository;

    public function __construct(ThemeRepository $themeRepository)
    {
        $this->themeRepository = $themeRepository;
    }

    public function index(): AnonymousResourceCollection
    {
        return ThemeResource::collection($this->themeRepository->all()->sortBy('label'));
    }
}
