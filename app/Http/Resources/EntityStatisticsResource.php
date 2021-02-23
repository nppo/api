<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class EntityStatisticsResource extends JsonResource
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
                'name'          => $entity,
                'count'         => ('App\Models\\' . Str::studly($entity))::count(),
                'countDisplay' => number_format(('App\Models\\' . Str::studly($entity))::count(), 0, ',', '.'),
            ])
            ->values()
            ->toArray();
    }
}
