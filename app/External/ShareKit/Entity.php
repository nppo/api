<?php

declare(strict_types=1);

namespace App\External\ShareKit;

use Illuminate\Support\Arr;

abstract class Entity
{
    protected array $meta;
    protected array $data;

    public function __construct(array $meta, array $data)
    {
        $this->meta = $meta;
        $this->data = $data;
    }

    public function __get($name)
    {
        return Arr::get($this->data, $name);
    }
}
