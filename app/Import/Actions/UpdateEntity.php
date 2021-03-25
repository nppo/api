<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Enumerators\ImportType;
use App\Enumerators\ProductTypes;
use App\Import\Action;
use App\Models\ExternalResource;
use App\Models\Person;
use App\Models\Product;
use App\Transforming\Map;
use App\Transforming\Mapping;
use Carbon\Carbon;
use Illuminate\Support\Str;
use InvalidArgumentException;

class UpdateEntity implements Action
{
    public function process(ExternalResource $externalResource): void
    {
        $mapping = $this->resolveMapping($externalResource->type);

        $output = [];
        $mapping->apply($externalResource->data, $output);

        if ($externalResource->entity) {
            $externalResource->entity->update($output);

            return;
        }

        $class = $this->resolveModelClass($externalResource->type);
        $model = $class::create($output);

        $externalResource->entity()->associate($model);
        $externalResource->save();
    }

    private function resolveModelClass(string $type): string
    {
        switch ($type) {
            case ImportType::PRODUCT:
                return Product::class;
            case ImportType::PERSON:
                return Person::class;
            default:
                throw new InvalidArgumentException('No class provided for type' . $type);
        }
    }

    private function resolveMapping(string $type): Mapping
    {
        switch ($type) {
            case ImportType::PRODUCT:
                return new Mapping([
                    new Map('title', 'title', null, Str::random()),
                    new Map('dateIssued', 'published_at', 'date', Carbon::now()),
                    new Map('abstract', 'description', null, ''),
                    new Map('::DOES_NOT_EXIST::', 'type', null, ProductTypes::EMPTY),
                ]);
            case ImportType::PERSON:
                return new Mapping([
                    new Map('person.id', 'identifier'),
                    new Map('person.name', 'first_name', 'firstName'),
                    new Map('person.name', 'last_name', 'lastName'),
                    new Map('role', 'function', 'personFunction'),
                ]);
            default:
                throw new InvalidArgumentException('No map provided for type' . $type);
        }
    }
}
