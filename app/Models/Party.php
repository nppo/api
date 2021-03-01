<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Way2Web\Force\AbstractModel;

class Party extends AbstractModel
{
    public function products(): MorphToMany
    {
        return $this->morphToMany(Product::class, 'contributable');
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class);
    }
}
