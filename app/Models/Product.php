<?php

declare(strict_types=1);

namespace App\Models;

use Laravel\Scout\Searchable;
use Way2Web\Force\AbstractModel;

class Product extends AbstractModel
{
    use Searchable;

    public function toSearchableArray(): array
    {
        return [
            'id'          => $this->getKey(),
            'title'       => $this->title,
            'description' => $this->description,
        ];
    }
}
