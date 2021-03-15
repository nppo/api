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
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('contributable_id');
            $table->string('contributable_type');

            $table->boolean('is_owner');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributables');
    }
}
