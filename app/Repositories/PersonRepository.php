<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\Filters;
use App\Models\Person;
use App\Models\User;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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

    /** @param mixed $id */
    public function show($id): Model
    {
        return $this
            ->with([
                'parties',
                'products.children',
                'projects',
                'skills',
                'themes',
                'media',
                'attributes',
                'values',
            ])
            ->findOrFail($id);
    }

    public function search(string $query): self
    {
        $this
            ->builder
            ->with(['keywords']);

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

    public function limit(int $amount): self
    {
        $this->builder->limit($amount);

        return $this;
    }

    public function cursorPaginate(int $perPage = self::DEFAULT_PER_PAGE): CursorPaginator
    {
        return $this->builder->cursorPaginate($perPage);
    }

    public function get(): Collection
    {
        return $this->builder->get();
    }

    public function createFull(array $data, User $user): Person
    {
        $person = new Person($data);

        $person->save();

        $user->person()->associate($person);
        $user->save();

        return $person;
    }
}
