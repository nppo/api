<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Keyword;
use Illuminate\Contracts\Pagination\Paginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Way2Web\Force\Repository\AbstractRepository;

class KeywordRepository extends AbstractRepository
{
    public function model(): string
    {
        return Keyword::class;
    }

    public function index(): Paginator
    {
        return QueryBuilder::for($this->makeQuery())
            ->defaultSort('label')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('label'),
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('label'),
            ])
            ->jsonPaginate();
    }

    public function show(string $id): Keyword
    {
        /** @var Keyword */
        $keyword = $this->findOrFail($id);

        return $keyword;
    }

    public function createFull(array $data): Keyword
    {
        /** @var Keyword */
        $keyword = Keyword::create($data);

        return $keyword;
    }

    public function updateFull(string $id, array $data): Keyword
    {
        /** @var Keyword */
        $keyword = $this->findOrFail($id);

        $keyword->update($data);

        return $keyword;
    }

    public function deleteFull(string $id): Keyword
    {
        /** @var Keyword */
        $keyword = $this->findOrFail($id);

        $keyword->delete();

        return $keyword;
    }
}
