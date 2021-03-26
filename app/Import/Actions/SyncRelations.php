<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Enumerators\ImportType;
use App\Import\Actions\Support\WorksWithRelations;
use App\Models\ExternalResource;
use Illuminate\Support\Arr;

/**
 * Syncs relations from the parent external resources entity to this entity.
 */
class SyncRelations extends AbstractAction
{
    use WorksWithRelations;

    public function __construct()
    {
        $this
            ->onlyWhen(function (ExternalResource $externalResource) {
                return
                    !is_null($externalResource->entity) &&
                    !is_null($externalResource->parent) &&
                    !is_null($externalResource->parent->entity);
            });
    }

    public function process(ExternalResource $externalResource): void
    {
        $parentEntity = $externalResource->parent->entity;

        foreach ($this->syncableTypes($externalResource) as $importType) {
            $relation = $this->guessPluralRelationMethod($importType);

            if (method_exists($parentEntity, $relation)) {
                $externalResource->entity->{$relation}()->syncWithoutDetaching(
                    $parentEntity->{$relation}
                );
            }
        }
    }

    private function syncableTypes(ExternalResource $externalResource)
    {
        $types = ImportType::asArray();

        // Prevents Products from interlinking to make sure it does not become a circle relation
        if ($externalResource->type == ImportType::PRODUCT) {
            $types = Arr::except($types, ImportType::getKey(ImportType::PRODUCT));
        }

        return $types;
    }
}
