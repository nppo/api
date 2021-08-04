<?php

declare(strict_types=1);

namespace App\Http\Requests\Party;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'min:2'],
            'description' => ['nullable', 'string', 'min:3'],
        ];
    }
}
