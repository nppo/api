<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Way2Web\Force\AbstractModel;

class Person extends AbstractModel
{
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function tags(): HasManyThrough
    {
        return $this->hasManyThrough(Tag::class, Product::class);
    }
}
