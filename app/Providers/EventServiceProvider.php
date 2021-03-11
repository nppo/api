<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\BootstrapApplication;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\SURFconext\SURFconextExtendSocialite;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SocialiteWasCalled::class => [
            SURFconextExtendSocialite::class,
        ],
        MigrationsEnded::class => [
            BootstrapApplication::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
