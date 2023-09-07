<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovingBox extends Model
{
    use HasFactory;

    protected $table = "moving_box";

    protected $fillable =
    [
        'balance',
        'id_sector',
        'id_bank',
        'status',
        'responsible',
        'id_cost_center',
    ];

    function bank()
    {
        return $this->belongsTo(Bank::class, 'id_bank', 'id');
    }

    function sector()
    {
        return $this->belongsTo(Sectors::class, 'id_sector', 'id');
    }

    function user()
    {
        return $this->belongsTo(User::class, 'responsible', 'id');
    }
}
