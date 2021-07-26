<?php

declare(strict_types=1);

namespace Database\Seeders\Support;

use App\Interfaces\HasMetaData;
use App\Models\Value;

trait SeedsMetadata
{
    public function seedMetadata(HasMetaData $hasMetaData): void
    {
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
