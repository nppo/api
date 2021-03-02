<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\Filters;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Way2Web\Force\Repository\AbstractRepository;

class ProjectRepository extends AbstractRepository
{
    protected ?Builder $builder = null;

    public function __construct()
    {
        $this->builder = $this->makeQuery();
    }

    public function model(): string
    {
        return Project::class;
    }

    public function show($id)
    {
        return $this
            ->with([
                'owner.tags',
                'parties',
                'people.tags',
                'products',
                'tags',
                'themes',
            ])
            ->withCount('likes')
            ->findOrFail($id);
    }

    public function search(string $query): self
    {
        $this
            ->builder
            ->with(['themes', 'tags'])
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
                    $query->whereIn('themes.id', $value);
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
