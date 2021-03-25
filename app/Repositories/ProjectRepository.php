<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\Filters;
use App\Models\Project;
use App\Models\Structure;
use Illuminate\Database\Eloquent\Builder;
use Way2Web\Force\Repository\AbstractRepository;

class ProjectRepository extends AbstractRepository
{
    protected ?Builder $builder = null;

    private StructureRepository $structureRepository;

    public function __construct(StructureRepository $structureRepository)
    {
        $this->builder = $this->makeQuery();

        $this->structureRepository = $structureRepository;
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
                'values',
                'attributes',
                'values',
                'media',
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
                    $query->whereIn('id', $value);
                });
            }
        }

        return $this;
    }

    public function getMetaDataFields(): ?array
    {
        return optional(
            $this
                ->structureRepository
                ->makeQuery()
                ->with([
                    'attributes'
                ])
                ->where('label', $this->model())
                ->first()
            )
            ->toArray();
    }

    public function get()
    {
        return $this->builder->get();
    }
}
