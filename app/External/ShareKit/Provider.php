<?php

declare(strict_types=1);

namespace App\External\ShareKit;

use App\External\ShareKit\Facades\ShareKit;
use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('ShareKit', ShareKit::class);

        $this->app->when(\App\External\ShareKit\Connection::class)
            ->needs('$baseUrl')
            ->give(config('sharekit.baseUrl'));

        $this->app->when(\App\External\ShareKit\Connection::class)
            ->needs('$token')
            ->give(config('sharekit.token'));

        dump('Registered');
    }
}
