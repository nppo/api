<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Way2Web\Force\Http\Resource;

class EntityStatisticsResource extends Resource
{
    /**
     * @param Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return array
     */
    public function toArray($request): array
    {
        return collect($this->resource->entities)
            ->map(fn ($entity) => [
                'name'  => $entity,
                'count' => ('App\Models\\' . Str::studly($entity))::count(),
            ])
            ->values()
            ->toArray();
    }
}
