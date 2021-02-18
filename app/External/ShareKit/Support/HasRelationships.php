<?php

declare(strict_types=1);

namespace App\External\ShareKit\Support;

use App\External\ShareKit\Entity;
use Illuminate\Support\Arr;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;

trait HasRelationships
{
    use HasAttributes;

    protected function hasMany(string $class, string $path = null, string $entityType = null)
    {
        $entityType = $this->getRelationEntityType($class, $entityType);
        $path = $this->getRelationPath($class, $path);

        return collect($this->getAttribute($path))
            ->map(function (array $data) use ($class, $entityType): Entity {
                return new $class(
                    Arr::get($data, $entityType),
                    Arr::except($data, $entityType)
                );
            });
    }

    protected function getRelationEntityType(string $class, ?string $entityType = null)
    {
        if (!$entityType) {
            $entityType = class_basename($class);
        }

        return Str::lower($entityType);
    }

    protected function getRelationPath(string $class, ?string $path = null)
    {
        if (!$path) {
            $path = Pluralizer::plural($class);
        }

        return $path;
    }
}
