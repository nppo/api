<?php

declare(strict_types=1);

use App\Enumerators\ImportDriver;
use App\Enumerators\ImportType;
use App\Enumerators\ProductTypes;
use App\Enumerators\TagTypes;
use App\Import\Actions\IdentifyEntity;
use App\Import\Actions\SplitResource;
use App\Import\Actions\SyncEntity;
use App\Import\Actions\SyncParentRelations;
use App\Import\Actions\SyncRelations;
use App\Import\Resolvers\CompositeResolver;
use App\Import\Resolvers\Person\EmailResolver;
use App\Import\Resolvers\Person\UserEmailResolver;
use App\Import\Resolvers\Product\IdResolver;
use App\Import\Resolvers\Project\IdResolver as ProjectIdResolver;
use App\Import\Resolvers\Tag\LabelResolver;
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
                    new IdentifyEntity(
                        new CompositeResolver([
                            new EmailResolver(),
                            new UserEmailResolver(),
                        ]),
                    ),
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
                    new IdentifyEntity(
                        new LabelResolver()
                    ),
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
                    new IdentifyEntity(
                        new LabelResolver()
                    ),
                    new SyncEntity(),
                    new SyncParentRelations(),
                ],
                'mapping' => new Mapping([
                    new Map('label', 'label'),
                ]),
            ],
            ImportType::THEME => [
                'actions' => [
                    new IdentifyEntity(
                        new LabelResolver(TagTypes::THEME)
                    ),
                    new SyncEntity(),
                    new SyncParentRelations(),
                ],
                'mapping' => new Mapping([
                    new Map('label', 'label'),
                    new Map('::DOES_NOT_EXIST::', 'type', null, TagTypes::THEME),
                ]),
            ],
            ImportType::PRODUCT => [
                'actions' => [
                    new IdentifyEntity(
                        new IdResolver(),
                    ),
                    new SyncParentRelations(),
                ],
                'mapping' => new Mapping([
                    new Map('identifier', 'id'),
                ]),
            ],
            ImportType::PROJECT => [
                'actions' => [
                    new IdentifyEntity(
                        new ProjectIdResolver(),
                    ),
                    new SyncParentRelations(),
                ],
                'mapping' => new Mapping([
                    new Map('identifier', 'id'),
                ]),
            ],
            ImportType::ARTICLE => [
                'actions' => [
                    new SyncEntity(),

                    (new SplitResource(ImportType::TAG, 'tags.*'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return Arr::get($data, 'label');
                        }),

                    (new SplitResource(ImportType::THEME, 'themes.*'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return Arr::get($data, 'label');
                        }),

                    (new SplitResource(ImportType::PRODUCT, 'related_products.*'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return Arr::get($data, 'identifier');
                        }),

                    (new SplitResource(ImportType::PROJECT, 'related_projects.*'))
                        ->resolveIdentifierUsing(function (array $data) {
                            return Arr::get($data, 'identifier');
                        }),
                ],
                'mapping' => new Mapping([
                    new Map('title', 'title'),
                    new Map('preview.url', 'preview_url', 'strapi_content'),
                    new Map('summary', 'summary'),
                    new Map('header', 'header', 'strapi_content'),
                    new Map('content', 'content', 'strapi_content'),
                ]),
            ],
        ]
    ],
];
