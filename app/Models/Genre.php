<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = ['name'];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }

    public static function findOrCreateByName($name)
    {
        $genre = static::where('name', $name)->first();
        if (!$genre) {
            $genre = static::create(['name' => $name]);
        }
        return $genre->id;
    }

    public static function insertGetId(array $values, $sequence = null)
    {
        // 名前で既存のGenreを確認し、存在する場合はそのIDを返す
        if (isset($values['name'])) {
            $existing = static::where('name', $values['name'])->first();
            if ($existing) {
                return $existing->id;
            }
        }
        
        // 存在しない場合は通常通り挿入
        return \Illuminate\Support\Facades\DB::table((new static)->getTable())->insertGetId($values, $sequence);
    }
}
