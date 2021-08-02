<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\Filters;
use App\Models\Article;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Way2Web\Force\Repository\AbstractRepository;

class ArticleRepository extends AbstractRepository
{
    protected ?Builder $builder = null;

    public function __construct()
    {
        $this->builder = $this->makeQuery();
    }

    public function model(): string
    {
        return Article::class;
    }

    public function index(): Collection
    {
        return $this
            ->makeQuery()
            ->orderBy('title')
            ->get();
    }

    public function show($id): Model
    {
        return $this
            ->with(['tags', 'themes'])
            ->findOrFail($id);
    }

    public function search(string $query): self
    {
        $this
            ->builder
            ->with(['tags', 'themes'])
            ->withCount('likes');

        if ($query !== '') {
            $this->builder->where('title', 'LIKE', "%{$query}%");
        }

        return $this;
    }

    public function filter(array $filters = []): self
    {
        foreach ($filters as $key => $value) {
            if ($key === Filters::THEMES) {
                $this->builder->whereHas('themes', function ($query) use ($value): void {
                    $query->whereIn('id', $value);
                });
            }
        }

        return $this;
    }

    public function orderBy(string $column, string $order = 'asc'): self
    {
        $this->builder->orderBy($column, $order);

        return $this;
    }

    public function limit(int $amount): self
    {
        $this->builder->limit($amount);

        return $this;
    }

    public function cursorPaginate(int $perPage = self::DEFAULT_PER_PAGE): CursorPaginator
    {
        return $this->builder->cursorPaginate($perPage);
    }

    public function get()
    {
        return $this->builder->get();
    }
}
