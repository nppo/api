<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Structure;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface HasMetaData
{
    public function resolveStructure(): Structure;

    public function structure(): BelongsTo;
}
