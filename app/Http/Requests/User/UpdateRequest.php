<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'roles'        => ['nullable', 'array', 'min:1'],
            'roles.*.name' => ['required', 'string'],
        ];
    }

    public function data(): array
    {
        return Arr::except($this->validated(), ['roles']);
    }

    public function roles(): array
    {
        return Arr::only($this->validated(), ['roles']);
    }
}
