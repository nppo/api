<?php

declare(strict_types=1);

namespace Database\Seeders\Support;

use App\Enumerators\Disks;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait SeedsMedia
{
    protected function getRandomMediaFile(string $type)
    {
        return Arr::random($this->getMediaOptions($type));
    }

    protected function getMediaOptions(string $type): array
    {
        return Storage::disk(Disks::SEEDING)
            ->files('product/' . $type);
    }

    protected function randomFileName(string $originalFileName): string
    {
        return Str::uuid()->toString() . '.' . Str::afterLast($originalFileName, '.');
    }
}
