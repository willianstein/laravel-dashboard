<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryMovingBox extends Model
{
    use HasFactory;

    protected $table = "history_moving_box";

    protected $fillable =
    [
        'favored',
        'goal',
        'type',
        'value',
        'status',
        'id_moving_box'
    ];

    function movingBox()
    {
        return $this->belongsTo(MovingBox::class, 'id_moving_box')->with(['bank', 'user']);
    }
}
