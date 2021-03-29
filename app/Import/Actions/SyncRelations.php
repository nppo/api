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
                    !empty($externalResource->children);
            });
    }

    public function process(ExternalResource $externalResource): void
    {
        $externalResource
            ->parents
            ->filter(function (ExternalResource $parentResource) {
                return !is_null($parentResource->entity);
            })
            ->each(function (ExternalResource $parentResource) use ($externalResource): void {
                foreach ($this->syncableTypes($externalResource) as $importType) {
                    $relation = $this->guessPluralRelationMethod($importType);

                    if (method_exists($parentResource->entity, $relation)
                        && method_exists($externalResource->entity, $relation)) {
                        $externalResource->entity->{$relation}()->syncWithoutDetaching(
                            $parentResource->entity->{$relation}
                        );
                    }
                }
            });
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
