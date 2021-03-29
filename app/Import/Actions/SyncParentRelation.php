<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Import\Actions\Support\WorksWithRelations;
use App\Models\ExternalResource;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Links the child entity to the parent entity.
 */
class SyncParentRelation extends AbstractAction
{
    use WorksWithRelations;

    public function __construct()
    {
        $this->onlyWhen(function (ExternalResource $externalResource): bool {
            return !is_null($externalResource->entity) &&
                !is_null($externalResource->parent) &&
                !is_null($externalResource->parent->entity);
        });
    }

    public function process(ExternalResource $externalResource): void
    {
        /** @var Model */
        $model = $externalResource->entity;

        /** @var Model */
        $parent = $externalResource->parent->entity;

        $relation = $this->guessRelation($parent, $model);

        if (method_exists($relation, 'syncWithoutDetaching')) {
            $relation->syncWithoutDetaching($model);

            return;
        }

        throw new InvalidArgumentException('Unable to link parent relation');
    }
}
