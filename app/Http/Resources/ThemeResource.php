<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enumerators\Action;

class ThemeResource extends TagResource
{
    protected array $permissions = [
        Action::UPDATE,
    ];
}
