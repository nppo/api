<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('product_content', function (Blueprint $table): void {
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('child_id')->constrained('products')->onDelete('cascade');

            $table->unique(['product_id', 'child_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
}
