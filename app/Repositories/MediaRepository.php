<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Media;
use Way2Web\Force\Repository\AbstractRepository;

class MediaRepository extends AbstractRepository
{
    public function model(): string
    {
        return Media::class;
    }
}
