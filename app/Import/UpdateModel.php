<?php

declare(strict_types=1);

namespace App\Import\SyncModel;

use App\Models\ExternalResource;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class UpdateModel
{
    protected ExternalResource $externalResource;

    public function __construct(ExternalResource $externalResource)
    {
        $this->externalResource = $externalResource;
    }

    public function handle(): void
    {
        $model = $this->externalResource->entity;

        if (!$model instanceof Model) {
            throw new InvalidArgumentException('Linked entity is not an existing model');
        }
    }
}
