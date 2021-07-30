<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Way2Web\Force\AbstractModel;
use Way2Web\Force\HasUuid;

class Structure extends AbstractModel
{
    use HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'label',
    ];

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }
}
