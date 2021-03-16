<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'       => ['required'],
            'description' => ['nullable'],
            'purpose'     => ['nullable'],
        ];
    }
}
