<?php

declare(strict_types=1);

namespace App\Enumerators;

use Way2Web\Force\Enum;

class Disks extends Enum
{
    public const SURF_PUBLIC = 'surf_public';
    public const SURF_PRIVATE = 'surf_private';

    public const SEEDING = 'seeding';
}
