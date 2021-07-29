<?php

namespace App\Http\Requests\Tag;

use App\Enumerators\TagTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['nullable', 'string', Rule::in(TagTypes::asArray())],
            'label' => ['required', 'string', 'min:3'],
        ];
    }
}
