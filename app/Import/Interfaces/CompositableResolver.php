<?php

namespace App\Import\Interfaces;

interface CompositableResolver extends ModelResolver
{
    public function canResolve(array $data): bool;
}
