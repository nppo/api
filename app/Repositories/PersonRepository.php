<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\Filters;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Way2Web\Force\Repository\AbstractRepository;

class PersonRepository extends AbstractRepository
{
    protected ?Builder $builder = null;

    public function __construct()
    {
        $this->builder = $this->makeQuery();
    }

    public function model(): string
    {
        return Person::class;
    }

    public function search(string $query): self
    {
        $this
            ->builder
            ->with(['tags']);

        if ($query !== '') {
            $this
                ->builder
                ->where('first_name', 'LIKE', "%{$query}%")
                ->orWhere('last_name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%");
        }

        return $this;
    }

    public function filter(array $filters = []): self
    {
        foreach ($filters as $key => $value) {
            if ($key === Filters::TAGS) {
                $this->builder->whereHas('tags', function ($query) use ($value): void {
                    $query->whereIn('tag.id', $value);
                });
            }
        }

        return $this;
    }

    public function get()
    {
        return $this->builder->get();
    }
}
