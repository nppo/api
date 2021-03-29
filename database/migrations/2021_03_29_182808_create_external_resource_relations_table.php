<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalResourceRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('external_resource_relations', function (Blueprint $table): void {
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('child_id');

            $table->foreign('parent_id')
                ->references('id')
                ->on('external_resources')
                ->cascadeOnDelete();

            $table->foreign('child_id')
                ->references('id')
                ->on('external_resources');

            $table->timestamps();
            $table->index(['parent_id', 'child_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('external_resource_relations');
    }
}
