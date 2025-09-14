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
        Schema::create('sheets', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->integer('column')->comment('列');
            $table->string('row', 255)->comment('行');
            $table->unsignedBigInteger('screen_id')->comment('スクリーンID');
            $table->foreign('screen_id')->references('id')->on('screens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sheets');
    }
};
