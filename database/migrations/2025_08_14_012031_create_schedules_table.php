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
        Schema::create('schedules', function (Blueprint $table) {
            // ID - unsigned big integer, auto_increment, primary key
            $table->id()->comment('ID');

            // movie_id - unsigned big integer, foreign key(movies), index
            // foreignIdを使うと外部キー制約とインデックスが自動で設定される
            $table->foreignId('movie_id')
                ->constrained('movies')
                ->onDelete('cascade')
                ->comment('列');

            // start_time - time
            $table->time('start_time')->comment('上映開始時刻');

            // end_time - time
            $table->time('end_time')->comment('上映終了時刻');

            // created_at, updated_at - datetime
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
