<?php

declare(strict_types=1);

namespace App\Providers;

use App\Auth\Passport\Client;
use App\External\ShareKit\Provider as ShareKitProvider;
use App\External\Strapi\Provider as StrapiProvider;
use App\Models\User;
use App\Observers\UserObserver;
use App\Transforming\Repository;
use App\Transforming\Support\RegistersTransformers;
use App\Transforming\Transformers\Date;
use App\Transforming\Transformers\FirstName;
use App\Transforming\Transformers\LastName;
use App\Transforming\Transformers\PersonFunction;
use App\Transforming\Transformers\ProductTitleTransformer;
use App\Transforming\Transformers\ProductTypeTransformer;
use App\Transforming\Transformers\Strapi\ContentTransformer;
use App\Transforming\Transformers\Theme;
use Fruitcake\Cors\HandleCors;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;

class AppServiceProvider extends ServiceProvider
{
    use RegistersTransformers;

    public $singletons = [
        Repository::class => Repository::class,
    ];

    public $transformers = [
        'date'                 => Date::class,
        'firstName'            => FirstName::class,
        'lastName'             => LastName::class,
        'personFunction'       => PersonFunction::class,
        'sharekit_producttype' => ProductTypeTransformer::class,
        'sharekit_url_title'   => ProductTitleTransformer::class,
        'theme'                => Theme::class,
        'strapi_content'       => ContentTransformer::class,
    ];

    public function register(): void
    {
        $this->app->register(ShareKitProvider::class);
        $this->app->register(StrapiProvider::class);

        $this->registerPassport();

        $this->registerTransformers();
    }

    public function boot(): void
    {
        User::observe(UserObserver::class);
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
