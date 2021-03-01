<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table): void {
            $table->id();

            $table->string('identifier');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('about')->nullable();
            $table->string('email')->nullable();
            $table->string('function')->nullable();
            $table->string('phone')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people');
    }
}
