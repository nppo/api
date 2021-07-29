<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContributablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('contributables', function (Blueprint $table): void {
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();

            $table->uuidMorphs('contributable');

            $table->boolean('is_owner')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributables');
    }
}
