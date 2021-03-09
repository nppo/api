<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Person;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class PersonUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['string', 'required'],
            'last_name'  => ['string', 'required'],
            'about'      => ['string', 'nullable'],
            'tags'       => ['array', 'nullable'],
        ];
    }

    public function attachTags(Person $person, Collection $tags): void
    {
        $person
            ->tags()
            ->saveMany($tags);
    }
}
