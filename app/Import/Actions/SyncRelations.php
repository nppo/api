<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Enumerators\ImportType;
use App\Import\Action;
use App\Models\ExternalResource;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SyncRelations implements Action
{
    protected ?Closure $onlyWhenCallback = null;

    public function process(ExternalResource $externalResource): void
    {
        if ($this->onlyWhenCallback) {
            $callback = $this->onlyWhenCallback;

            if (!$callback($externalResource)) {
                return;
            }
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

    public function onlyWhen(?Closure $closure): self
    {
        $this->onlyWhenCallback = $closure;

        return $this;
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
