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
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('taggable_id');
            $table->string('taggable_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
}
