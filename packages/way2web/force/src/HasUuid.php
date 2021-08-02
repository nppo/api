<?php

declare(strict_types=1);

namespace Way2Web\Force;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuid
{
    public static function bootHasUuid(): void
    {
        static::creating(function (Model $model): void {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
}
