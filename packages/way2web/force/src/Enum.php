<?php

declare(strict_types=1);

namespace Way2Web\Force;

use InvalidArgumentException;
use ReflectionClass;

class Enum
{
    private const IDENTIFIER = 'id';

    private const DEFAULT_KEY = 'name';

    private const ARRAY_DELIMITER = ',';

    public static function asArray(): array
    {
        $class = get_called_class();

        return (new ReflectionClass(new $class()))->getConstants();
    }

    public static function asArrayString(string $delimiter = self::ARRAY_DELIMITER): string
    {
        return implode($delimiter, self::asArray());
    }

    public static function asReferableArray(string $valueKey = self::DEFAULT_KEY): array
    {
        $class = get_called_class();

        $constants = (new ReflectionClass(new $class()))->getConstants();

        $referableArray = [];

        foreach (array_values($constants) as $key => $value) {
            $id = $key + 1;

            $referableArray[$key][self::IDENTIFIER] = $id;
            $referableArray[$key][$valueKey] = $value;
        }

        return $referableArray;
    }

    public static function getByReferableKey($id, string $key = self::DEFAULT_KEY)
    {
        $referableArray = self::asReferableArray();

        return $referableArray[$id - 1][$key];
    }

    public static function getByReferableValue($value, string $key = self::DEFAULT_KEY)
    {
        $referableArray = self::asReferableArray();

        $requestedValue = array_filter($referableArray, function ($referableValue) use ($value, $key) {
            return $value === $referableValue[self::DEFAULT_KEY];
        });

        return reset($requestedValue)[$key];
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
