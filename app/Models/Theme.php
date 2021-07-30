<?php

declare(strict_types=1);

namespace App\Models;

use App\Enumerators\TagTypes;
use App\Models\Support\IsTag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Scout\Searchable;
use Way2Web\Force\AbstractModel;

class Theme extends AbstractModel
{
    use Searchable, IsTag;

    protected static ?string  $tagType = TagTypes::THEME;

    protected $table = 'tags';

    protected $fillable = [
        'label',
        'type',
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
