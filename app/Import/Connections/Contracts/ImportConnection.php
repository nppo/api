<?php

declare(strict_types=1);

namespace App\Import\Connections\Contracts;

interface ImportConnection
{
    public function import(): void;
}
