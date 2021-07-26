<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductContentTable extends Migration
{
    public function up(): void
    {
        Schema::create('product_content', function (Blueprint $table): void {
            $table->foreignUuid('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignUuid('child_id')->constrained('products')->onDelete('cascade');

            $table->unique(['product_id', 'child_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_content');
    }
}
