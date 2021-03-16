<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetadatablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('metadatables', function (Blueprint $table): void {
            $table->foreignId('structure_id');
            $table->morphs('entity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadatables');
    }
}
