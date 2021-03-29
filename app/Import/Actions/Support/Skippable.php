<?php

declare(strict_types=1);

namespace App\Import\Actions\Support;

use App\Models\ExternalResource;
use Closure;

trait Skippable
{
    protected ?Closure $skippableClosure = null;

    public function skipWhen(?Closure $closure): self
    {
        $this->skippableClosure = $closure;

        return $this;
    }

    protected function shouldBeSkipped(ExternalResource $externalResource): bool
    {
        if ($this->skippableClosure) {
            $callback = $this->skippableClosure;

            return $callback($externalResource);
        }

        return false;
    }
}
