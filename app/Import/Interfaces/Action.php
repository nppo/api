<?php

declare(strict_types=1);

namespace App\Import\Interfaces;

use App\Models\ExternalResource;

interface Action
{
    public function process(ExternalResource $externalResource): void;
}
