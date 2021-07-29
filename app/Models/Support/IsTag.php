<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Builder;

trait IsTag
{
    public static function bootIsTag(): void
    {
        static::addGlobalScope(function (Builder $builder) {
            $builder->where('type', static::$tagType);
        });
    }
}
