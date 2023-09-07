<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillsToReceive extends Model
{
    use HasFactory;

    protected $table = "bills_to_receive";

    protected $fillable =
    [
        'description',
        'value',
        'date_competence',
        'id_cost_center',
        'id_bank',
        'id_favored',
        'repetition',
        'status',
        'date_received'
    ];

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
