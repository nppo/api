<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Party;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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

    public function get()
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

    public function show($id)
    {
        return $this
            ->with([
                'parties',
                'projects',
                'products',
            ])
            ->findOrFail($id);
    }
}
