<?php

declare(strict_types=1);

namespace App\Models\Support;

use App\Models\ExternalResource;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasExternalResource
{
    public function externalResource(): MorphOne
    {
        return $this->morphOne(ExternalResource::class);
    }

    public function hasExternalResource(): bool
    {
        return !is_null($this->externalResource);
    }
}
