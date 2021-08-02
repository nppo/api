<?php

declare(strict_types=1);

namespace App\External\ShareKit\Support;

trait HasAttributes
{
    protected array $attributes = [];

    public function hasAttribute(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    /** @return mixed */
    public function getAttribute(string $key)
    {
        if (!$this->hasAttribute($key)) {
            return;
        }

        return $this->attributes[$key];
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /** @return mixed */
    public function getRawAttribute(string $key)
    {
        return $this->attributes[$key];
    }

    /** @param mixed $value */
    public function setRawAttribute(string $key, $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    protected function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }
}
