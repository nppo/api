<?php

declare(strict_types=1);

use App\Enumerators\ImportDriver;
use App\Enumerators\ImportType;
use App\Enumerators\ProductTypes;
use App\Import\Actions\SplitResource;
use App\Import\Actions\SyncEntity;
use App\Import\Actions\SyncParentRelation;
use App\Import\Actions\SyncRelations;
use App\Models\ExternalResource;
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
                    new SyncParentRelation(),
                    (new SplitResource(ImportType::PERSON, 'authors.*'))
                        ->skipWhen(function (ExternalResource $externalResource) {
                            return !Arr::has($externalResource->data, 'authors');
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

                    (new SyncRelations())
                        ->onlyWhen(function (ExternalResource $externalResource) {
                            return !is_null($externalResource->parent) &&
                                !is_null($externalResource->parent->entity) &&
                                !is_null($externalResource->entity);
                        }),
                ],
                'mapping' => new Mapping([
                    new Map('fileName', 'title', null, fn () => Str::random()),
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
                    new SyncParentRelation(),
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
                    new SyncEntity(),
                    new SyncParentRelation(),
                ],
                'mapping' => new Mapping([
                    new Map('[0]', 'name'),
                ]),
            ],
            ImportType::TAG => [
                'actions' => [
                    new SyncEntity(),
                    new SyncParentRelation(),
                ],
                'mapping' => new Mapping([
                    new Map('[0]', 'label'),
                ]),
            ],
        ],
    ],
];
