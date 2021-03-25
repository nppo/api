<?php

declare(strict_types=1);

namespace App\Transforming\Support;

use App\Transforming\Map;
use Flow\JSONPath\JSONPath;
use InvalidArgumentException;

trait MapsData
{
    use TransformsData;

    public function map(Map $map, array $input, &$output): void
    {
        if (is_array($output)) {
            $this->mapArray($map, $input, $output);

            return;
        }

        if (is_object($output)) {
            $this->mapObject($map, $input, $output);

            return;
        }

        throw new InvalidArgumentException('Output type is not supported');
    }

    protected function mapArray(Map $map, array $input, array &$output): void
    {
        $output[$map->getDestination()] = $this->retrieveInputValue($map, $input);
    }

    protected function mapObject(Map $map, array $input, object &$output): void
    {
        $output->{$map->getDestination()} = $this->retrieveInputValue($map, $input);
    }

    /** @return mixed */
    protected function retrieveInputValue(Map $map, array $input)
    {
        $value = (new JSONPath($input))->find($map->getOrigin())->first();

        if (is_null($value)) {
            return $map->getDefault();
        }

        if ($value instanceof JSONPath) {
            $value = $value->getData();
        }

        if ($map->getTransformerType()) {
            return $this->transform($map->getTransformerType(), $value);
        }

        return $value;
    }
}
