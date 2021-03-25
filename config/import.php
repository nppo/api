<?php

declare(strict_types=1);

use App\Enumerators\ImportDriver;
use App\Enumerators\ImportType;
use App\Enumerators\ProductTypes;
use App\Import\Actions\RelateResource;
use App\Import\Actions\SplitResource;
use App\Import\Actions\UpdateEntity;
use App\Models\ExternalResource;
use App\Transforming\Map;
use App\Transforming\Mapping;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return [
    'drivers' => [
        ImportDriver::SHAREKIT => [
            ImportType::PRODUCT => [
                'actions' => [
                    new UpdateEntity(),
                    new RelateResource(),
                    (new SplitResource(ImportType::PERSON, 'authors.*'))
                        ->onlyWhen(function (ExternalResource $externalResource) {
                            return Arr::has($externalResource->data, 'authors');
                        })
                        ->resolveIdentifierUsing(function (array $data) {
                            return Arr::get($data, 'person.id');
                        }),
                    (new SplitResource(ImportType::PARTY, 'publisher'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return Arr::first($data);
                        }),

                    (new SplitResource(ImportType::PRODUCT, 'link.*'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return Hash::make(Arr::get($data, 'url'));
                        }),

                    (new SplitResource(ImportType::PRODUCT, 'file.*'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return Str::after(Arr::get($data, 'url'), 'objectstore/');
                        }),
                ],
                'mapping' => new Mapping([
                    new Map('title', 'title', null, fn () => Str::random()),
                    new Map('dateIssued', 'published_at', 'date', fn () => Carbon::now()),
                    new Map('abstract', 'description', null, ''),
                    new Map('url', 'type', 'sharekit_producttype', ProductTypes::EMPTY),
                ]),
            ],
            ImportType::PERSON => [
                'actions' => [
                    new UpdateEntity(),
                    new RelateResource(),
                ],
                'mapping' => new Mapping([
                    new Map('person.id', 'identifier'),
                    new Map('person.name', 'first_name', 'firstName'),
                    new Map('person.name', 'last_name', 'lastName'),
                    new Map('role', 'function', 'personFunction'),
                ]),
            ],
            ImportType::PARTY => [
                'actions' => [
                    new UpdateEntity(),
                    new RelateResource(),
                ],
                'mapping' => new Mapping([
                    new Map('[0]', 'name'),
                ]),
            ],
        ],
    ],
];
