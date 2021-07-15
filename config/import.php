<?php

declare(strict_types=1);

use App\Enumerators\ImportDriver;
use App\Enumerators\ImportType;
use App\Enumerators\ProductTypes;
use App\Import\Actions\SplitResource;
use App\Import\Actions\SyncEntity;
use App\Import\Actions\SyncParentRelations;
use App\Import\Actions\SyncRelations;
use App\Transforming\Map;
use App\Transforming\Mapping;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

return [
    'drivers' => [
        ImportDriver::SHAREKIT => [
            ImportType::PRODUCT => [
                'actions' => [
                    new SyncEntity(),
                    new SyncParentRelations(),

                    (new SplitResource(ImportType::PERSON, 'authors.*'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return Arr::get($data, 'person.id');
                        }),

                    (new SplitResource(ImportType::PARTY, 'publisher'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return Arr::first($data);
                        }),

                    (new SplitResource(ImportType::PRODUCT, 'link.*'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return md5(Arr::get($data, 'url'));
                        }),

                    (new SplitResource(ImportType::PRODUCT, 'file.*'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return Str::after(Arr::get($data, 'url'), 'objectstore/');
                        }),

                    (new SplitResource(ImportType::TAG, 'keywords.*'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return Arr::first($data);
                        }),

                    new SyncRelations(),
                ],
                'mapping' => new Mapping([
                    // Asign a random filename as default or try determening it from the URL
                    new Map('::DOES_NOT_EXIST::', 'title', null, fn () => Str::random()),
                    new Map('url', 'title', 'sharekit_url_title'),
                    new Map('fileName', 'title'),

                    new Map('title', 'title'),
                    new Map('dateIssued', 'published_at', 'date', fn () => Carbon::now()),
                    new Map('abstract', 'description', null, ''),
                    new Map('url', 'type', 'sharekit_producttype', ProductTypes::COLLECTION),
                    new Map('url', 'link'),
                ]),
            ],
            ImportType::PERSON => [
                'actions' => [
                    new SyncEntity(),
                    new SyncParentRelations(),
                ],
                'mapping' => new Mapping([
                    new Map('person.id', 'identifier'),
                    new Map('person.name', 'first_name', 'firstName'),
                    new Map('person.name', 'last_name', 'lastName'),
                    new Map('person.email', 'email'),
                    new Map('role', 'function', 'personFunction'),
                ]),
            ],
            ImportType::PARTY => [
                'actions' => [
                    new SyncEntity(),
                    new SyncParentRelations(),
                ],
                'mapping' => new Mapping([
                    new Map('[0]', 'name'),
                ]),
            ],
            ImportType::TAG => [
                'actions' => [
                    new SyncEntity(),
                    new SyncParentRelations(),
                ],
                'mapping' => new Mapping([
                    new Map('[0]', 'label'),
                ]),
            ],
        ],

        ImportDriver::STRAPI => [
            ImportType::TAG => [
                'actions' => [
                    new SyncEntity(),
                    new SyncParentRelations(),
                ],
                'mapping' => new Mapping([
                    new Map('label', 'label'),
                ]),
            ],
            ImportType::ARTICLE => [
                'actions' => [
                    new SyncEntity(),

                    (new SplitResource(ImportType::TAG, 'tags.*'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return Arr::get($data, 'label');
                        }),
                ],
                'mapping' => new Mapping([
                    new Map('title', 'title'),
                ]),
            ],
        ]
    ],
];
