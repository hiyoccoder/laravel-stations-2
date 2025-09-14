<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SheetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $screen = \App\Models\Screen::first();

        if (!$screen) {
            $screen = \App\Models\Screen::create(['name' => 'Default Screen']);
        }

        if (!\App\Models\Sheet::where('id', 1)->exists()) {
            \App\Models\Sheet::create([
                'id' => 1,
                'row' => 1,
                'column' => 1,
                'screen_id' => $screen->id,
            ]);
        }

        if (!\App\Models\Sheet::where('id', 2)->exists()) {
            \App\Models\Sheet::create([
                'id' => 2,
                'row' => 1,
                'column' => 2,
                'screen_id' => $screen->id,
            ]);
        }

        if (!\App\Models\Sheet::where('id', 3)->exists()) {
            \App\Models\Sheet::create([
                'id' => 3,
                'row' => 2,
                'column' => 1,
                'screen_id' => $screen->id,
            ]);
        }
    }
}
