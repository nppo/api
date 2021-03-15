<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikeablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('likeables', function (Blueprint $table): void {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->morphs('likeable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likeables');
    }
}
