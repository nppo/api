<?php

declare(strict_types=1);

namespace Tests\Helpers;

use ReflectionClass;

class Instance
{
    /**
     * @param object $instance
     *
     * @return mixed
     */
    public static function getProperty(&$instance, string $property)
    {
        $reflection = new ReflectionClass($instance);

        $property = $reflection->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($instance);
    }
}
