<?php

declare(strict_types=1);

namespace App\Providers;

use App\Auth\Passport\Client;
use App\External\ShareKit\Provider as ShareKitProvider;
use App\Transforming\Repository;
use Fruitcake\Cors\HandleCors;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        Repository::class => Repository::class,
    ];

    public function register(): void
    {
        $this->app->register(ShareKitProvider::class);

        $this->registerPassport();
    }

    public function boot(): void
    {
        //
    }

    protected function registerPassport(): void
    {
        Passport::ignoreMigrations();
        Passport::useClientModel(Client::class);

        Passport::tokensExpireIn(now()->addMinutes(30));
        Passport::refreshTokensExpireIn(now()->addDays(30));

        Passport::routes(
            function (RouteRegistrar $routeRegistrar): void {
                $routeRegistrar->forAuthorization();
                $routeRegistrar->forAccessTokens();
                $routeRegistrar->forTransientTokens();
            },
            [
                'prefix'     => 'auth/oauth/',
                'as'         => 'auth.',
                'middleware' => [HandleCors::class],
            ]
        );
    }
}
