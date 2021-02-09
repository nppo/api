<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Way2Web\Force\AbstractModel;

class Party extends AbstractModel
{
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
