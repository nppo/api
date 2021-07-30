<?php

declare(strict_types=1);

namespace Database\Seeders\Support;

use App\Interfaces\HasMetaData;
use App\Models\Attribute;
use App\Models\Value;
use Illuminate\Database\Eloquent\Model;

trait SeedsMetadata
{
    public function seedMetadata(HasMetaData $hasMetaData): void
    {
        if (!$hasMetaData instanceof Model) {
            return;
        }

        /** @var Attribute $attribute */
        foreach ($hasMetaData->attributes()->get() as $attribute) {
            if (rand(0, 1)) {
                Value::factory()
                    ->for($attribute)
                    ->for($hasMetaData, 'entity')
                    ->create();
            }
        }
    }
}
