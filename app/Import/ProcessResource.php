<?php

declare(strict_types=1);

namespace App\Import;

use App\Enumerators\ImportType;
use App\Import\Actions\RelateResource;
use App\Import\Actions\SplitResource;
use App\Import\Actions\UpdateEntity;
use App\Models\ExternalResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ProcessResource
{
    protected ExternalResource $externalResource;

    public function __construct(ExternalResource $externalResource)
    {
        $this->externalResource = $externalResource;
    }

    public function handle(): void
    {
        foreach ($this->resolveActions() as $action) {
            if (!$action instanceof Action) {
                throw new InvalidArgumentException('Action does not implement interface');
            }

            $action->process($this->externalResource);
        }
    }

    private function resolveActions(): array
    {
        $actions = [new UpdateEntity(), new RelateResource()];

        if ($this->externalResource->type === ImportType::PRODUCT) {
            $actions[] = (new SplitResource(ImportType::PERSON, 'authors.*'))
                ->resolveIdentifierUsing(function (array $data) {
                    return Arr::get($data, 'person.id');
                });

            // $actions[] = (new SplitResource(ImportType::PRODUCT, 'link.*'))
            //     ->resolveIdentifierUsing(function (array $data) {
            //         return Arr::get($data, 'url');
            //     });

            // $actions[] = (new SplitResource(ImportType::PRODUCT, 'file.*'))
            //     ->resolveIdentifierUsing(function (array $data) {
            //         return Str::after(Arr::get($data, 'url'), 'objectstore');
            //     });
        }

        return $actions;
    }
}
