<?php

declare(strict_types=1);

namespace App\Import\Interfaces;

interface CompositableResolver extends ModelResolver
{
    public function canResolve(array $data): bool;
}
