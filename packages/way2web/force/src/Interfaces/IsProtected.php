<?php

declare(strict_types=1);

namespace Way2Web\Force\Interfaces;

interface IsProtected
{
    /**
     * Returns an array of keys with possible permissions.
     */
    public function aggregatePermissions(): array;
}
