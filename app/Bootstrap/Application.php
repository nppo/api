<?php

declare(strict_types=1);

namespace App\Bootstrap;

use Illuminate\Support\Facades\App;

class Application
{
    protected array $bootstrappers = [
        Permissions::class,
        Roles::class,
    ];

    public function bootstrap(): void
    {
        foreach ($this->bootstrappers as $bootstrapper) {
            App::make($bootstrapper)->bootstrap();
        }
    }
}
