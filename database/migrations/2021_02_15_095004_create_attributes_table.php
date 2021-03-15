<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesTable extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('structure_id');
            $table->string('label');

            $table->timestamps();

            $table->unique(['structure_id', 'label']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
}
