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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');                    // ファクトリーの 'title'
            $table->string('image_url')->nullable();    // ファクトリーの 'image_url'
            $table->integer('published_year');          // ファクトリーの 'published_year'
            $table->text('description')->nullable();    // ファクトリーの 'description'
            $table->boolean('is_showing')->default(false); // ファクトリーの 'is_showing'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
