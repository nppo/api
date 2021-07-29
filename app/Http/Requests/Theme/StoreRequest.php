<?php

namespace App\Http\Requests\Theme;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'label' => ['required', 'array', 'min:3']
        ];
    }
}
