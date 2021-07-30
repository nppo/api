<?php

declare(strict_types=1);

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Builder;

trait IsTag
{
    public static function bootIsTag(): void
    {
        static::addGlobalScope(function (Builder $builder): void {
            $builder->where('type', static::$tagType);
        });
    }
}
