<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Bootstrap\Application;

class BootstrapApplication
{
    private Application $bootstrapper;

    public function __construct(Application $application)
    {
        $this->bootstrapper = $application;
    }

    public function handle(): void
    {
        $this->bootstrapper->bootstrap();
    }
}
