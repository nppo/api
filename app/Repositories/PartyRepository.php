<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Party;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Way2Web\Force\Repository\AbstractRepository;

class PartyRepository extends AbstractRepository
{
    protected ?Builder $builder = null;

    public function __construct()
    {
        $this->builder = $this->makeQuery();
    }

    public function model(): string
    {
        return Party::class;
    }

    public function search(string $query): self
    {
        $this
            ->builder
            ->with(['products']);

        if ($query !== '') {
            $this->builder->where('name', 'LIKE', "%{$query}%");
        }

        return $this;
    }

    public function get(): Collection
    {
        return $this->builder->get();
    }

    public function index(): Collection
    {
        return $this
            ->makeQuery()
            ->orderBy('name')
            ->get();
    }

    /** @param mixed $id */
    public function show($id): Model
    {
        return $this
            ->with([
                'parties',
                'projects',
                'products',
                'media',
            ])
            ->findOrFail($id);
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
}
