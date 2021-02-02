<?php

declare(strict_types=1);

return [
    'url' => env(
        'SHAREKIT_URL',
        'https://surfsharekit.surf.staging2.zooma.nl/api/jsonapi/channel/v1/nppo/'
    ),

    'token' => env('SHAREKIT_TOKEN'),
];
