<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValuesTable extends Migration
{
    public function up(): void
    {
        Schema::create('values', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('attribute_id');
            $table->morphs('entity');
            $table->text('value');

            $table->timestamps();

            $table->unique(['attribute_id', 'entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('values');
    }
}
