<?php

declare(strict_types=1);

namespace App\External\ShareKit\Support;

trait HasPivot
{
    protected array $pivot = [];

    /** @return mixed */
    public function getPivot(?string $key = null)
    {
        if (!$key) {
            return $this->pivot;
        }

        return $this->pivot[$key];
    }

    public function hasPivot(string $key): bool
    {
        return array_key_exists($key, $this->pivot);
    }

    protected function setPivot(array $pivot): self
    {
        $this->pivot = $pivot;

        return $this;
    }
}
