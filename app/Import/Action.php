<?php

declare(strict_types=1);

namespace App\Import;

use App\Models\ExternalResource;

interface Action
{
    public function process(ExternalResource $externalResource): void;
}
