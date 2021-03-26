<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Enumerators\ProductTypes;
use App\Helpers\Structure as StructureHelper;
use App\Models\Attribute;
use App\Models\Person;
use App\Models\Project;
use App\Models\Structure;

class Structures
{
    protected array $classes = [
        Project::class,
        Person::class,
    ];

    protected array $productAttributes = [
        'identifier',
    ];

    public function bootstrap(): void
    {
        foreach ($this->classes as $class) {
            Structure::updateOrCreate(['label' => $class]);
        }

        foreach (ProductTypes::asArray() as $type) {
            $structure = Structure::updateOrCreate([
                'label' => StructureHelper::labelForProductType($type),
            ]);

            foreach ($this->productAttributes as $attribute) {
                Attribute::updateOrCreate([
                    'structure_id' => $structure->id,
                    'label'        => $attribute,
                ]);
            }
        }
    }
}
