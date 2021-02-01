<?php

declare(strict_types=1);

namespace Way2Web\Force;

use InvalidArgumentException;
use ReflectionClass;

class Enum
{
    public static function asArray(): array
    {
        $class = get_called_class();

        return (new ReflectionClass(new $class()))->getConstants();
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public static function getKey($value)
    {
        foreach (self::asArray() as $key => $enumValue) {
            if ($value === $enumValue) {
                return $key;
            }
        }

        throw new InvalidArgumentException('Unable to find key for ' . get_called_class());
    }
}
