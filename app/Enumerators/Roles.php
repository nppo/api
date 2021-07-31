<?php

declare(strict_types=1);

namespace App\Enumerators;

use Way2Web\Force\Enum;

class Roles extends Enum
{
    public const RESEARCHER = [
        Permissions::PROJECTS_CREATE,
        Permissions::PROJECTS_UPDATE,
        Permissions::PRODUCTS_CREATE,
        Permissions::PRODUCTS_UPDATE,
        Permissions::PEOPLE_CREATE,
        Permissions::PEOPLE_UPDATE,
    ];

    public const PLATFORM_ADMIN = [
        Permissions::PROJECTS_CREATE,
        Permissions::PROJECTS_UPDATE,
        Permissions::PRODUCTS_CREATE,
        Permissions::PRODUCTS_UPDATE,
        Permissions::PEOPLE_CREATE,
        Permissions::PEOPLE_UPDATE,
        Permissions::THEME_CREATE,
        Permissions::THEME_UPDATE,
        Permissions::TAG_CREATE,
        Permissions::TAG_UPDATE,
        Permissions::TAG_DELETE,
        Permissions::PARTY_CREATE,
        Permissions::PARTY_UPDATE,
        Permissions::PARTY_DELETE,
        Permissions::USER_CREATE,
        Permissions::USER_UPDATE,
        Permissions::USER_DELETE,
    ];
}
