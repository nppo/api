<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCooperablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('cooperables', function (Blueprint $table): void {
            $table->foreignUuid('project_id')->constrained()->onDelete('cascade');

            $table->uuidMorphs('cooperable');

            $table->boolean('is_owner')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperables');
    }
}
