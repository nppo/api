<?php

declare(strict_types=1);

namespace App\Transforming;

use App\Transforming\Interfaces\Transformer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;

class Repository
{
    protected array $transformers = [];

    public function for(string $type): Transformer
    {
        if (!$this->exists($type)) {
            throw new InvalidArgumentException("Transformer for type {$type} was not found");
        }

        return App::make($this->transformers[$type]);
    }

    public function exists(string $type): bool
    {
        return Arr::exists($this->transformers, $type);
    }

    public function register(string $type, string $class): self
    {
        if ($this->exists($type)) {
            throw new InvalidArgumentException("Transformer for type {$type} is already registered");
        }

        if (!class_exists($class)) {
            throw new InvalidArgumentException('Tried registerting a transformer class that does not exist');
        }

        $this->transformers[$type] = $class;

        return $this;
    }

    public function flush(): self
    {
        $this->transformers = [];

        return $this;
    }

    public function all(): array
    {
        return $this->transformers;
    }
}
