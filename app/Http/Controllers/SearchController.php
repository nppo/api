<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\SearchResource;
use App\Repositories\SearchRepository;

class SearchController extends Controller
{
    private SearchRepository $searchRepository;

    public function __construct(SearchRepository $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function search(SearchRequest $request): SearchResource
    {
        return $this
            ->searchRepository
            ->searchFor(
                $request->getTypes(),
                $request->getQuery(),
                $request->getFilters(),
            )
            ->toResource();
    }
}
