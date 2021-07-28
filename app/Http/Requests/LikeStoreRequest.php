<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Article;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LikeStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'likable_type' => [
                'string',
                'required',
                Rule::in([
                    Product::class,
                    Project::class,
                    Party::class,
                    Person::class,
                    Article::class,
                ]), ],
            'likable_id' => ['required'],
        ];
    }
}
