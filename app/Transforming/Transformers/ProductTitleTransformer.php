<?php

declare(strict_types=1);

namespace App\Transforming\Transformers;

use App\Transforming\Interfaces\Transformer;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProductTitleTransformer implements Transformer
{
    private const MAX_TITLE_LENGTH = 255;

    private const DOWNLOAD_HEADER = 'Content-Disposition';

    private const TITLE_REGEX = '/<title[^>]*>(.*?)<\/title>/ims';

    public function transform($value)
    {
        if (is_string($value)) {
            try {
                $response = Http::get(trim($value));

                if ($response->ok()) {
                    $title = $this->findHtmlTitle($response);

                    if (is_null($title)) {
                        $title = $this->findByHeader($response);
                    }

                    if (!is_null($title) && !empty($title) && strlen($value) <= self::MAX_TITLE_LENGTH) {
                        return html_entity_decode(trim($title));
                    }
                }
            } catch (Exception $exception) {
                return;
            }
        }
    }

    private function findHtmlTitle(Response $response): ?string
    {
        $matches = [];

        preg_match(self::TITLE_REGEX, $response->__toString(), $matches);

        if (empty($matches)) {
            return null;
        }

        return Str::after(Str::before(Arr::first($matches), '</title>'), '>');
    }

    private function findByHeader(Response $response): ?string
    {
        if (Arr::has($response->headers(), self::DOWNLOAD_HEADER)) {
            $header = Arr::first(Arr::get($response->headers(), self::DOWNLOAD_HEADER));

            return Str::between(
                Str::after(
                    $header,
                    'filename='
                ),
                '"',
                '"'
            );
        }

        return null;
    }
}
