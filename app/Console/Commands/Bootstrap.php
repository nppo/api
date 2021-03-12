<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Bootstrap\Application;
use Illuminate\Console\Command;

class Bootstrap extends Command
{
    protected $signature = 'bootstrap';

    protected $description = 'Bootstraps the application';

    public function handle(Application $application): void
    {
        $application->bootstrap();
    }
}
