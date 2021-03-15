<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Way2Web\Force\AbstractModel;

class Attribute extends AbstractModel
{
    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }
}
