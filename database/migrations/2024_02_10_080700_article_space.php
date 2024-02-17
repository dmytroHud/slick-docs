<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_space', function (Blueprint $table) {
            $table->foreignId('space_id')->references('id')->on('spaces')->onDelete('cascade');
            $table->foreignId('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->integer('order');

            $table->primary(['space_id', 'article_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_space');
    }
};
