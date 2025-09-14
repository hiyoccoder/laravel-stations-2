<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'column',
        'row',
        'screen_id',
    ];
    public function schedules()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }
}
