<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enumerators\Permissions;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Product $product): bool
    {
        if (!$user->can(Permissions::PRODUCTS_UPDATE)) {
            return false;
        }

        if (!$user->person) {
            return false;
        }

        if (!$product->people->contains($user->person)) {
            return false;
        }

        return true;
    }
}
