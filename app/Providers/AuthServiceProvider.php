<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Product;
use App\Models\Project;
use App\Policies\ProductPolicy;
use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Project::class => ProjectPolicy::class,
         Product::class => ProductPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
