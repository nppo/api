<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelatablesTable extends Migration
{
    public function up()
    {
        Schema::create('relatables', function (Blueprint $table) {
            $table->foreignId('article_id');

            $table->uuidMorphs('relatable');
        });
    }

    public function down()
    {
        Schema::dropIfExists('relatables');
    }
}
