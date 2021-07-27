<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaggablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('taggables', function (Blueprint $table): void {
            $table->foreignUuid('tag_id')->constrained()->cascadeOnDelete();

            $table->uuidMorphs('taggable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
}
