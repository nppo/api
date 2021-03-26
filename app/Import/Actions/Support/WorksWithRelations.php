<?php

declare(strict_types=1);

namespace App\Import\Actions\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

trait WorksWithRelations
{
    protected function guessRelationMethod(string $class): string
    {
        return class_basename($class);
    }

    protected function guessPluralRelationMethod(string $class): string
    {
        return Str::plural($this->guessRelationMethod($class));
    }

    /** @param Model|string $entity */
    protected function guessRelation(Model $model, $entity): ?Relation
    {
        if ($entity instanceof Model) {
            $entity = get_class($entity);
        }

        $method = $this->guessRelationMethod($entity);

        if (method_exists($model, $method)) {
            return $model->{$method}();
        }

        $method = $this->guessPluralRelationMethod($entity);

        if (method_exists($model, $method)) {
            return $model->{$method}();
        }

        return null;
    }
}
