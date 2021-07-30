<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enumerators\Mimes;
use App\Enumerators\ProductTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => [Rule::in(ProductTypes::asArray())],

            'title'        => ['required'],
            'description'  => ['nullable'],
            'published_at' => ['nullable', 'date'],
            'summary'      => ['nullable'],

            'tags'         => ['array', 'nullable'],
            'tags.*.label' => ['required', 'string'],

            'themes'      => ['array', 'nullable'],
            'themes.*.id' => ['required', 'uuid'],

            'people'      => ['array', 'nullable'],
            'people.*.id' => ['required', 'uuid'],

            'parties'      => ['array', 'nullable'],
            'parties.*.id' => ['required', 'uuid'],

            'children'      => ['array', 'nullable', 'prohibited_unless:type,' . ProductTypes::COLLECTION],
            'children.*.id' => ['required', 'uuid'],

            'link' => ['prohibited_unless:type,' . ProductTypes::LINK, 'nullable', 'string', 'url'],
            'file' => [
                'required_without_all:link,children',
                'nullable',
                'mimes:' . Mimes::asArrayString(),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'published_at' => $this->get('publishedAt'),
        ]);
    }
}
