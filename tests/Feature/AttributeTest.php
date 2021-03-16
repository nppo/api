<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\Project;
use App\Models\Value;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class AttributeTest extends TestCase
{
    /** @test */
    public function it_will_load_the_value_relation_from_the_instance(): void
    {
        $attribute = Attribute::factory()->make([
            'id' => 125,
        ]);

        $project = Project::factory()
            ->make();

        $value = Value::factory()->make(['attribute_id' => $attribute->id, 'value' => '::STRING::']);

        $project->setRelation('values', Collection::make(
            [$value]
        ));

        $attribute->loadValueFrom($project);

        $this->assertTrue(
            $attribute->relationLoaded('value')
        );
    }
}
