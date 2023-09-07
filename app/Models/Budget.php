<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $table = "budget";

    function bank()
    {
        return $this->belongsTo(Bank::class, 'id_bank', 'id');
    }

    function billsToPay()
    {
        return $this->belongsTo(Financial::class, 'id_cost_center', 'id');
    }

    function partner()
    {
        return $this->belongsTo(Partners::class, 'id_favored', 'id');
    }
}
