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

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
