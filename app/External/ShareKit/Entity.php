<?php

declare(strict_types=1);

namespace App\External\ShareKit;

use App\External\ShareKit\Support\HasAttributes;
use App\External\ShareKit\Support\HasPivot;
use App\External\ShareKit\Support\HasRelationships;

abstract class Entity
{
    use HasAttributes;
    use HasRelationships;
    use HasPivot;

    public function __construct(array $attributes = [], array $pivot = [])
    {
        $this->setAttributes($attributes);
        $this->setPivot($pivot);
    }

    public function __get($name)
    {
        if (method_exists($this, $name)) {
            return $this->{$name}();
        }

        if ($this->hasAttribute($name)) {
            return $this->getAttribute($name);
        }

        return $this->{$name};
    }
}
