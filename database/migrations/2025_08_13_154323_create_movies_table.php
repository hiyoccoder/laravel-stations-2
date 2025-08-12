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
            $table->string('title', 255)->unique();
            $table->text('image_url');
            $table->integer('published_year');
            $table->tinyInteger('is_showing')->default(0);
            $table->text('description');
            $table->foreignId('genre_id')
                ->constrained('genres')
                ->onUpdate('cascade')    // ジャンルIDが変わったら映画も追従
                ->onDelete('restrict'); // ジャンルが使用中なら削除できない
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
