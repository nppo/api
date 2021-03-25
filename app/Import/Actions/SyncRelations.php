<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Enumerators\ImportType;
use App\Import\Actions\Support\Skippable;
use App\Import\Interfaces\Action;
use App\Models\ExternalResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SyncRelations implements Action
{
    use Skippable;

    public function process(ExternalResource $externalResource): void
    {
        if ($this->shouldBeSkipped($externalResource)) {
            return;
        }

        $parentEntity = $externalResource->parent->entity;

        foreach ($this->syncableRelations($externalResource) as $importType) {
            $relation = Str::plural(class_basename($importType));

            if (method_exists($parentEntity, $relation)) {
                $externalResource->entity->{$relation}()->syncWithoutDetaching(
                    $parentEntity->{$relation}
                );
            }
        }
    }

    private function syncableRelations(ExternalResource $externalResource)
    {
        $types = ImportType::asArray();

        if ($externalResource->type == ImportType::PRODUCT) {
            $types = Arr::except($types, ImportType::getKey(ImportType::PRODUCT));
        }

        return $types;
    }
}
