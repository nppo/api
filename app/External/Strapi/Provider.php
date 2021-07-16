<?php

declare(strict_types=1);

namespace App\External\Strapi;

use App\External\Strapi\Facades\Strapi;
use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('Strapi', Strapi::class);
    }
}
