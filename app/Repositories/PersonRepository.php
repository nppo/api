<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\Filters;
use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
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
                'products',
                'projects',
                'tags',
                'themes',
                'media',
            ])
            ->findOrFail($id);
    }

    public function update(array $data, $attributeValue, string $attributeField = self::DEFAULT_ATTRIBUTES_FIELD): int
    {
        Arr::forget(
            $data,
            [
                '_method',
                '_token',
            ]
        );

        $person = $this->makeQuery()->where($attributeField, $attributeValue)->first();

        if (isset($data['tags'])) {
            $person->tags()->sync(
                collect($data['tags'])
                ->map(fn ($tag) => $tag['id'])
            );

            unset($data['tags']);
        }

        return $this->makeQuery()->where($attributeField, $attributeValue)->update($data);
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
