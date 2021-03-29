<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ExternalResource extends Model
{
    protected $fillable = [
        'driver',
        'type',
        'external_identifier',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'external_resource_relations', 'child_id', 'parent_id');
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'external_resource_relations', 'parent_id', 'child_id');
    }
}
