<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    public function getIsShowingTextAttribute()
    {
        return $this->is_showing ? '上映中' : '上映予定';
    }

    protected $fillable = [
        'title',
        'image_url',
        'published_year',
        'is_showing',
        'description',
        'genre_id',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public static function insertGetId(array $values, $sequence = null)
    {
        // タイトルで既存のMovieを確認し、存在する場合はそのIDを返す
        if (isset($values['title'])) {
            $existing = static::where('title', $values['title'])->first();
            if ($existing) {
                return $existing->id;
            }
        }
        
        // 存在しない場合は通常通り挿入
        return \Illuminate\Support\Facades\DB::table((new static)->getTable())->insertGetId($values, $sequence);
    }
}
