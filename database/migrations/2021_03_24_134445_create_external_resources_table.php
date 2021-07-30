<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalResourcesTable extends Migration
{
    public function up(): void
    {
        Schema::create('external_resources', function (Blueprint $table): void {
            $table->id();

            $table->string('driver');
            $table->string('type');
            $table->string('external_identifier');

            $table->nullableUuidMorphs('entity');
            $table->json('data');

            $table->unique(['driver', 'type', 'external_identifier']);
            $table->unique(['entity_type', 'entity_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_resources');
    }
}
