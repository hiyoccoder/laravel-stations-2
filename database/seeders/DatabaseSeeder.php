<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Database\Seeder;

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
            Genre::firstOrCreate(['genre_name' => $genreName]);
        }

        // 作成されたジャンルのIDを取得
        $genreIds = Genre::pluck('id')->toArray();

        // MovieFactoryを修正して既存ジャンルを使用
        Movie::factory(10)->make()->each(function ($movie) use ($genreIds) {
            $movie->genre_id = fake()->randomElement($genreIds);
            $movie->save();
        });
    }
}
