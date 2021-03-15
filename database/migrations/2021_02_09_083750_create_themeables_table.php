<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThemeablesTable extends Migration
{
    public function up(): void
    {
        Schema::create('themeables', function (Blueprint $table): void {
            $table->foreignId('theme_id')->constrained()->onDelete('cascade');

            $table->morphs('themeable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themeables');
    }
}
