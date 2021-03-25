<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Enumerators\ImportType;
use App\Import\Interfaces\Action;
use App\Models\ExternalResource;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Tag;
use App\Transforming\Mapping;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

class SyncEntity implements Action
{
    public function process(ExternalResource $externalResource): void
    {
        $mapping = $this->resolveMapping($externalResource->driver, $externalResource->type);

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
            case ImportType::PARTY:
                return Party::class;
            case ImportType::TAG:
                return Tag::class;
            default:
                throw new InvalidArgumentException('No class provided for type ' . $type);
        }
    }

    private function resolveMapping(string $driver, string $type): Mapping
    {
        $configKey = 'import.drivers.' . $driver . '.' . $type . '.mapping';

        if (!Config::has($configKey)) {
            throw new InvalidArgumentException('Provided resource has no mapping');
        }

        return Config::get($configKey);
    }
}
