<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('structure_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('type');
            $table->string('title');
            $table->text('summary')->nullable();
            $table->text('description');

            $table->text('link')->nullable();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}
