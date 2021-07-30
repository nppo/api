<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\TagTypes;
use App\Models\Theme;
use Illuminate\Contracts\Pagination\Paginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Way2Web\Force\Repository\AbstractRepository;

class ThemeRepository extends AbstractRepository
{
    public function model(): string
    {
        return Theme::class;
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
            ->paginate();
    }

    public function show(string $id): Theme
    {
        return $this->findOrFail($id);
    }

    public function createFull(array $data): Theme
    {
        /** @var Theme */
        $theme = Theme::create(
            array_merge(
                ['type' => TagTypes::THEME],
                $data
            )
        );

        return $theme;
    }

    public function updateFull(string $id, array $data): Theme
    {
        /** @var Theme */
        $theme = $this->findOrFail($id);

        $theme->update($data);

        return $theme;
    }
}
