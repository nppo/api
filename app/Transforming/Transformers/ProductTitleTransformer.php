<?php

declare(strict_types=1);

namespace App\Transforming\Transformers;

use App\Enumerators\ProductTypes;
use App\Transforming\Interfaces\Transformer;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProductTitleTransformer implements Transformer
{
    private const MAX_TITLE_LENGTH = 255;

    public function transform($value)
    {
        if (is_string($value)) {
            if ((new ProductTypeTransformer())->transform($value) != ProductTypes::LINK) {
                return;
            }

            try {
                $response = Http::get(trim($value));

                if ($response->ok()) {
                    $title = $this->findTitle($response->__toString());

                    if (!is_null($title) && !empty($title) && strlen($value) <= self::MAX_TITLE_LENGTH) {
                        return html_entity_decode($title);
                    }
                }
            } catch (Exception $exception) {
                return;
            }
        }
    }

    private function findTitle(string $response): ?string
    {
        $matches = [];

        preg_match('/<title[^>]*>(.*?)<\/title>/ims', $response, $matches);

        if (empty($matches)) {
            return null;
        }

        return Str::after(Str::before(Arr::first($matches), '</title>'), '>');
    }
}
