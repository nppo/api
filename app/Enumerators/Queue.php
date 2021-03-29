<?php

declare(strict_types=1);

namespace App\Enumerators;

use Way2Web\Force\Enum;

class Queue extends Enum
{
    public const MEDIA = 'media';

    public const IMPORT_EXTERNAL = 'import_external';

    public const IMPORT_INTERNAL = 'import_internal';
}
