<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    use HasFactory;

    protected $table = "cost_center";

    protected $fillable =
    [
        'id',
        'code',
        'parent_code',
        'name',
        'type',
        'condition',
    ];
}
