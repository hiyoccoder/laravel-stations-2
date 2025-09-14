<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 関連テーブルをクリア
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('reservations')->delete();
        DB::table('schedules')->delete();
        DB::table('movies')->delete();
        DB::table('genres')->delete();
        DB::table('sheets')->truncate();
        DB::table('screens')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 事前に固定のジャンルを作成
        $genreNames = [
            'アクション',
            'コメディ',
            'ドラマ',
            'ホラー',
            'ロマンス',
            'SF',
            'スリラー',
            'アニメ',
            'ドキュメンタリー',
            'ミュージカル'
        ];

        foreach ($genreNames as $genreName) {
            Genre::firstOrCreate(['name' => $genreName]);
        }
        // スクリーンは各テストで動的に作成されるため、基本スクリーンのみ作成
        DB::table('screens')->insert([
            ['id' => 1, 'name' => 'スクリーン3']
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('sheets')->insert([
            ['id' => 1, 'column' => 1, 'row' => 'a', 'screen_id' => 1],
            ['id' => 2, 'column' => 2, 'row' => 'a', 'screen_id' => 1],
            ['id' => 3, 'column' => 3, 'row' => 'a', 'screen_id' => 1],
            ['id' => 4, 'column' => 4, 'row' => 'a', 'screen_id' => 1],
            ['id' => 5, 'column' => 5, 'row' => 'a', 'screen_id' => 1],
            ['id' => 6, 'column' => 1, 'row' => 'b', 'screen_id' => 1],
            ['id' => 7, 'column' => 2, 'row' => 'b', 'screen_id' => 1],
            ['id' => 8, 'column' => 3, 'row' => 'b', 'screen_id' => 1],
            ['id' => 9, 'column' => 4, 'row' => 'b', 'screen_id' => 1],
            ['id' => 10, 'column' => 5, 'row' => 'b', 'screen_id' => 1],
            ['id' => 11, 'column' => 1, 'row' => 'c', 'screen_id' => 1],
            ['id' => 12, 'column' => 2, 'row' => 'c', 'screen_id' => 1],
            ['id' => 13, 'column' => 3, 'row' => 'c', 'screen_id' => 1],
            ['id' => 14, 'column' => 4, 'row' => 'c', 'screen_id' => 1],
            ['id' => 15, 'column' => 5, 'row' => 'c', 'screen_id' => 1],
        ]);
    }
}
