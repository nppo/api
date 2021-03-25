<?php

declare(strict_types=1);

namespace App\Transforming\Support;

use App\Transforming\Map;
use Closure;
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
        $value = $this->retrieveInputValue($map, $input);

        if (!is_null($value)) {
            $output[$map->getDestination()] = $value;
        }
    }

    protected function mapObject(Map $map, array $input, object &$output): void
    {
        $value = $this->retrieveInputValue($map, $input);

        if (!is_null($value)) {
            $output->{$map->getDestination()} = $value;
        }
    }

    /** @return mixed */
    protected function retrieveInputValue(Map $map, array $input)
    {
        $value = (new JSONPath($input))->find($map->getOrigin())->first();

        if (is_null($value)) {
            $default = $map->getDefault();

            if ($default instanceof Closure) {
                return $default($input);
            }

            return $default;
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
