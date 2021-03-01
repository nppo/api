<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('affiliables', function (Blueprint $table): void {
            $table->foreignId('party_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('affiliable_id');
            $table->string('affiliable_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliable');
    }
}
