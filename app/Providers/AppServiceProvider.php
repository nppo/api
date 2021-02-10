<?php

declare(strict_types=1);

namespace App\Providers;

use App\External\ShareKit\Provider as ShareKitProvider;
use App\Transforming\Repository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        Repository::class => Repository::class,
    ];

    public function register(): void
    {
        $this->app->register(ShareKitProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
