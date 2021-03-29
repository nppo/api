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

            $table->unsignedBigInteger('parent_id')->nullable();

            $table->foreign('parent_id')
                ->references('id')
                ->on('external_resources')

                ->onDelete('CASCADE');

            $table->string('driver');
            $table->string('type');
            $table->string('external_identifier')->nullable();

            $table->nullableMorphs('entity');
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