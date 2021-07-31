<?php

declare(strict_types=1);

namespace App\Enumerators;

use Way2Web\Force\Enum;

class Permissions extends Enum
{
    public const PROJECTS_CREATE = 'create projects';

    public const PROJECTS_UPDATE = 'update projects';

    public const PRODUCTS_CREATE = 'create products';

    public const PRODUCTS_UPDATE = 'update products';

    public const PEOPLE_CREATE = 'create people';

    public const PEOPLE_UPDATE = 'update people';

    public const THEME_CREATE = 'create theme';

    public const THEME_UPDATE = 'update theme';

    public const KEYWORD_CREATE = 'create keyword';

    public const KEYWORD_UPDATE = 'update keyword';

    public const KEYWORD_DELETE = 'delete keyword';

    public const PARTY_CREATE = 'create party';

    public const PARTY_UPDATE = 'update party';

    public const PARTY_DELETE = 'delete party';

    public const USER_CREATE = 'create user';

    public const USER_UPDATE = 'update user';

    public const USER_DELETE = 'delete user';
}
