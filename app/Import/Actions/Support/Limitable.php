<?php

declare(strict_types=1);

namespace App\Import\Actions\Support;

use App\Models\ExternalResource;
use Closure;

trait Limitable
{
    protected ?Closure $onlyWhenCallback = null;

    public function onlyWhen(?Closure $closure): self
    {
        $this->onlyWhenCallback = $closure;

        return $this;
    }

    protected function shouldRun(ExternalResource $externalResource): bool
    {
        if ($this->onlyWhenCallback) {
            $closure = $this->onlyWhenCallback;

            if (!$closure($externalResource)) {
                return false;
            }
        }

        return true;
    }
}
