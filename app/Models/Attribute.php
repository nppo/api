<?php

declare(strict_types=1);

namespace App\Models;

use App\Interfaces\HasMetaData;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Way2Web\Force\AbstractModel;
use Way2Web\Force\HasUuid;

class Attribute extends AbstractModel
{
    use HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    public $fillable = [
        'label',
        'structure_id',
    ];

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(Value::class);
    }

    public function loadValueFrom(HasMetaData $model): self
    {
        $this->setRelation('value', $model->values()->where('attribute_id', $this->getKey())->first());

        return $this;
    }
}
