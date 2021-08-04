<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enumerators\Filters;
use App\Models\Project;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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

    /** @param mixed $id */
    public function show($id): Model
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
                    'attributes',
                ])
                ->where('label', $this->model())
                ->first()
        )
            ->toArray();
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

    public function softDelete(string $id): bool
    {
        $project = $this->findOrFail($id);

        return $project->delete();
    }
}
