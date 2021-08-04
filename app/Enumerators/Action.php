<?php

declare(strict_types=1);

namespace App\Enumerators;

use Way2Web\Force\Enum;

class Action extends Enum
{
    const VIEW_ANY = 'viewAny';

    const CREATE = 'create';

    const UPDATE = 'update';

    const DELETE = 'delete';
}
