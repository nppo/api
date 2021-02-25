<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Party;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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

    public function index(): Collection
    {
        $this
            ->makeQuery()
            ->orderByDesc('created_at')
            ->get();
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
}
