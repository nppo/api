<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Import\Actions\Support\WorksWithRelations;
use App\Models\ExternalResource;
use InvalidArgumentException;

/**
 * Links the child entity to the parent entity.
 */
class SyncParentRelations extends AbstractAction
{
    use WorksWithRelations;

    public function __construct()
    {
        $this->onlyWhen(function (ExternalResource $externalResource): bool {
            return !is_null($externalResource->entity) &&
                !empty($externalResource->parents);
        });
    }

    public function process(ExternalResource $externalResource): void
    {
        foreach ($externalResource->parents as $parent) {
            if (is_null($parent->entity)) {
                return;
            }

            $this->updateRelation($externalResource, $parent);
        }
    }

    private function updateRelation(ExternalResource $externalResource, ExternalResource $parentResource): void
    {
        $relation = $this->guessRelation($parentResource->entity, $externalResource->entity);

        if (method_exists($relation, 'syncWithoutDetaching')) {
            $relation->syncWithoutDetaching($externalResource->entity);

            return;
        }

        throw new InvalidArgumentException('Unable to link parent relation');
    }
}
