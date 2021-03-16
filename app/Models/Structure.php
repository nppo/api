<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Way2Web\Force\AbstractModel;

class Structure extends AbstractModel
{
    protected $fillable = [
        'label',
    ];

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }
}
