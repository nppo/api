<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\Filters;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Project;
use App\Models\User;
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

    public function show($id)
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

    public function addLike($personId, string $likableType, string $likableId): Person
    {
        /** @var Person $person */
        $person = $this->findOrFail($personId);

        /** @var User $user */
        $user = $person->user;

        switch ($likableType) {
            case Product::class:
                $user->likedProducts()->toggle($likableId);
                break;
            case Project::class:
                $user->likedProjects()->toggle($likableId);
                break;
            case Party::class:
                $user->likedParties()->toggle($likableId);
                break;
            case Person::class:
                $user->likedPeople()->toggle($likableId);
                break;
        }

        return $person;
    }
}
