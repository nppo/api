<?php

declare(strict_types=1);

namespace App\Http\Requests\Theme;

use App\Enumerators\TagTypes;
use Illuminate\Validation\Rule;

class UpdateRequest extends StoreRequest
{
    public function rules(): array
    {
        return array_merge(
            [
                'type' => ['nullable', 'string', Rule::in([TagTypes::KEYWORD, TagTypes::THEME])],
            ],
            parent::rules()
        );
    }
}
