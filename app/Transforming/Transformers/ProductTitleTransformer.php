<?php

declare(strict_types=1);

namespace App\Transforming\Transformers;

use App\Enumerators\ProductTypes;
use App\Transforming\Interfaces\Transformer;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProductTitleTransformer implements Transformer
{
    public function transform($value)
    {
        if (is_string($value)) {
            if ((new ProductTypeTransformer())->transform($value) != ProductTypes::LINK) {
                return;
            }

            try {
                $response = Http::get(trim($value));

                if ($response->status() === 200) {
                    $title = Str::before(Str::after($response->__toString(), '<title>'), '</title>');

                    if (!empty($title)) {
                        return html_entity_decode($title);
                    }
                }
            } catch (Exception $exception) {
                return;
            }
        }
    }
}
