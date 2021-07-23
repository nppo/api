<?php

declare(strict_types=1);

namespace App\Observers;

use App\Import\Resolvers\Person\EmailResolver;
use App\Models\User;
use Illuminate\Support\Facades\App;

class UserObserver
{
    public function creating(User $user): void
    {
        $resolver = App::make(EmailResolver::class);
        $resolved = $resolver->resolve($user->toArray());

        if ($resolved) {
            $user->person()->associate($resolved);
        }
    }
}
