<?php

declare(strict_types=1);

namespace App\Models;

use App\Enumerators\TagTypes;
use App\Models\Support\IsTag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Scout\Searchable;
use Way2Web\Force\AbstractModel;
use Way2Web\Force\HasUuid;

class Keyword extends AbstractModel
{
    use Searchable;
    use IsTag;
    use HasUuid;

    protected static ?string  $tagType = TagTypes::KEYWORD;

    protected $table = 'tags';

    public $incrementing = false;

    protected $keyType = 'string';

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
        return $this->morphedByMany(Person::class, 'taggable', 'taggables', 'tag_id', 'taggable_id');
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'taggable', 'taggables', 'tag_id', 'taggable_id');
    }
}
