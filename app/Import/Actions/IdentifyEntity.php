<?php

declare(strict_types=1);

namespace App\Import\Actions;

use App\Import\Interfaces\ModelResolver;
use App\Models\ExternalResource;
use App\Transforming\Mapping;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

class IdentifyEntity extends AbstractAction
{
    protected ModelResolver $modelResolver;

    public function __construct(ModelResolver $modelResolver)
    {
        $this->modelResolver = $modelResolver;

        $this->onlyWhen(function (ExternalResource $externalResource) {
            return is_null($externalResource->entity);
        });
    }

    public function process(ExternalResource $externalResource): void
    {
        $mapping = $this->resolveMapping($externalResource->driver, $externalResource->type);

        $output = [];
        $mapping->apply($externalResource->data, $output);

        $model = $this->modelResolver->resolve($output);

        if ($model) {
            $externalResource->entity()->associate($model);
            $externalResource->save();
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
