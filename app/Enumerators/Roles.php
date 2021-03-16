<?php

declare(strict_types=1);

namespace App\Enumerators;

use Way2Web\Force\Enum;

class Roles extends Enum
{
    public const RESEARCHER = [
        Permissions::PROJECTS_UPDATE,
        Permissions::PRODUCTS_CREATE,
        Permissions::PRODUCTS_UPDATE,
        Permissions::PEOPLE_UPDATE,
    ];

    public const PLATFORM_ADMIN = [
        Permissions::PROJECTS_UPDATE,
        Permissions::PRODUCTS_CREATE,
        Permissions::PRODUCTS_UPDATE,
        Permissions::PEOPLE_UPDATE,
    ];
}
