<?php

declare(strict_types=1);

namespace App\Import\Actions\Support;

use App\Models\ExternalResource;
use Closure;

trait Limitable
{
    public function onlyWhen(?Closure $closure)
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
