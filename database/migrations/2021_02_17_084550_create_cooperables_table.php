<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCooperablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cooperables', function (Blueprint $table): void {
            $table->foreignId('project_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('cooperable_id');
            $table->string('cooperable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cooperables');
    }
}
