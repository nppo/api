<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Way2Web\Force\AbstractModel;

class Theme extends AbstractModel
{
    use Searchable;

    public function toSearchableArray(): array
    {
        return [
            'id'          => $this->getKey(),
            'label'       => $this->label,
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
