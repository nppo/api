<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Import\Action;
use App\Models\ExternalResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use InvalidArgumentException;

class RelateResource implements Action
{
    public function process(ExternalResource $externalResource): void
    {
        if (!$externalResource->parent || !$externalResource->entity || !$externalResource->parent->entity) {
            return;
        }

        /** @var Model */
        $model = $externalResource->entity;

        /** @var Model */
        $parent = $externalResource->parent->entity;

        $relation = $this->guessRelation($parent, $model);

        $relation = $parent->{$relation}();
        $relation->attach($model);
    }

    private function guessRelation(Model $origin, Model $related): string
    {
        $class = class_basename(get_class($related));

        if (method_exists($origin, $class)) {
            return $class;
        }

        $plural = Str::plural($class);

        if (method_exists($origin, $plural)) {
            return $plural;
        }

        throw new InvalidArgumentException('Was not able to find relation ' . $plural);
    }
}
