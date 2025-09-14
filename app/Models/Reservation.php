<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'sheet_id',
        'name',
        'email',
        'date',
    ];
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
    public function sheet()
    {
        return $this->belongsTo(Sheet::class);
    }
}
