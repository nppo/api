<?php

declare(strict_types=1);

namespace App\Import\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface ModelResolver
{
    public function resolve(array $data): ?Model;
}
