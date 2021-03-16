<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Structure;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasMetaData
{
    public function resolveStructure(): Structure;

    public function structure(): BelongsTo;

    public function values(): MorphMany;

    public function attributes(): HasManyThrough;
}
