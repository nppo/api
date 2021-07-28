<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Person;
use App\Models\Product;
use App\Models\Project;
use App\Models\User;
use App\Policies\PersonPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /** @var array */
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Person::class  => PersonPolicy::class,
        Product::class => ProductPolicy::class,
        User::class    => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
