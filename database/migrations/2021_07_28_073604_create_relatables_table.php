<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelatablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('relatables', function (Blueprint $table): void {
            $table->foreignId('article_id');

            $table->uuidMorphs('relatable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relatables');
    }
}
