<?php

declare(strict_types=1);

namespace App\Http\Resources\Support;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Collection;

trait WithMetaData
{
    public function aggregateAttributes(): Collection
    {
        return $this->attributes->each(function (Attribute $attribute): void {
            $attribute->loadValueFrom($this->resource);
        });
    }
}
