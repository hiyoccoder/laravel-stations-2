<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Schedule; // ←追加
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
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

        // 作成されたジャンルのIDを取得
        $genreIds = Genre::pluck('id')->toArray();

        // MovieFactoryを修正して既存ジャンルを使用
        $movies = Movie::factory(10)->make()->each(function ($movie) use ($genreIds) {
            $movie->genre_id = fake()->randomElement($genreIds);
            $movie->save();
        });

        // 各映画に対してスケジュールを作成 ←追加
        foreach ($movies as $movie) {
            Schedule::factory()->count(fake()->numberBetween(2, 5))->create([
                'movie_id' => $movie->id
            ]);
        }

        // sheetsテーブルのマスターデータ投入
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sheets')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('sheets')->insert([
            ['id' => 1, 'column' => 1, 'row' => 'a'],
            ['id' => 2, 'column' => 2, 'row' => 'a'],
            ['id' => 3, 'column' => 3, 'row' => 'a'],
            ['id' => 4, 'column' => 4, 'row' => 'a'],
            ['id' => 5, 'column' => 5, 'row' => 'a'],
            ['id' => 6, 'column' => 1, 'row' => 'b'],
            ['id' => 7, 'column' => 2, 'row' => 'b'],
            ['id' => 8, 'column' => 3, 'row' => 'b'],
            ['id' => 9, 'column' => 4, 'row' => 'b'],
            ['id' => 10, 'column' => 5, 'row' => 'b'],
            ['id' => 11, 'column' => 1, 'row' => 'c'],
            ['id' => 12, 'column' => 2, 'row' => 'c'],
            ['id' => 13, 'column' => 3, 'row' => 'c'],
            ['id' => 14, 'column' => 4, 'row' => 'c'],
            ['id' => 15, 'column' => 5, 'row' => 'c'],
        ]);
    }
}
