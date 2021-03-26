<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Scout\Searchable;
use Way2Web\Force\AbstractModel;

class Theme extends AbstractModel
{
    use Searchable;

    protected $fillable = [
        'label',
    ];

    public function toSearchableArray(): array
    {
        return [
            'id'    => $this->getKey(),
            'label' => $this->label,
        ];
    }

    public function people(): MorphToMany
    {
        return $this->morphedByMany(Person::class, 'themeable');
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'themeable');
    }
}
