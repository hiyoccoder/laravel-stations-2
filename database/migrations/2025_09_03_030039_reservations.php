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
        Schema::create('reservations', function (Blueprint $table) {
            // ID (unsigned big integer, auto_increment)
            $table->id(); // ID

            // 上映日 (date)
            $table->date('date'); // 上映日

            // スケジュールID (unsigned big integer, foreign key(schedules), index)
            $table->unsignedBigInteger('schedule_id'); // スケジュールID
            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->index('schedule_id');

            // シートID (unsigned big integer, foreign key(sheets), index)
            $table->unsignedBigInteger('sheet_id'); // シートID
            $table->foreign('sheet_id')->references('id')->on('sheets');
            $table->index('sheet_id');

            // 予約者メールアドレス (VARCHAR(255))
            $table->string('email', 255); // 予約者メールアドレス

            // 予約者名 (VARCHAR(255))
            $table->string('name', 255); // 予約者名

            // 予約キャンセル済み (boolean, default false)
            $table->boolean('is_canceled')->default(false); // 予約キャンセル済み

            // 作成日時・更新日時 (datetime)
            $table->timestamps(); // 作成日時・更新日時

            // schedule_id, sheet_idの複合ユニーク制約
            $table->unique(['schedule_id', 'sheet_id'], 'unique_schedule_sheet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
