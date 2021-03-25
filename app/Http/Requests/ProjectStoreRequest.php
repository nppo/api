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

            'parties'      => ['nullable'],
            'parties.*'    => ['array', 'required'],
            'parties.*.id' => ['required', 'integer'],

            'products'      => ['nullable'],
            'products.*'    => ['array', 'required'],
            'products.*.id' => ['required', 'integer'],

            'meta'         => ['nullable'],
            'meta.*'       => ['array', 'required'],
            'meta.*.value' => ['nullable', 'string'],

            'project_picture' => [
                'sometimes',
                'image',
                'mimes:jpg,jpeg,bmp,png,gif',
                'max:' . $this->getMaxFileSize(),
            ],
        ];
    }

    private function getMaxFileSize(): string
    {
        return (string) config('media-library.max_file_size');
    }
}
