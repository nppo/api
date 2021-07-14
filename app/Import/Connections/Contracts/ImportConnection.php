<?php

namespace App\Import\Connections\Contracts;

interface ImportConnection
{
    public function import(): void;
}
