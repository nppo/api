<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SearchResource;
use App\Repositories\SearchRepository;

class HomeController extends Controller
{
    private SearchRepository $searchRepository;

    public function __construct(SearchRepository $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function discover(): SearchResource
    {
        return $this
            ->searchRepository
            ->discover()
            ->toResource();
    }
}
