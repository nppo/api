<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Way2Web\Force\AbstractModel;
use Way2Web\Force\HasUuid;

class Value extends AbstractModel
{
    use HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'value',
        'attribute_id',
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
