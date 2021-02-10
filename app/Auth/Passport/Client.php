<?php

declare(strict_types=1);

namespace App\Auth\Passport;

use Laravel\Passport\Client as PassportClient;

class Client extends PassportClient
{
    public function skipsAuthorization(): bool
    {
        return true;
    }
}
