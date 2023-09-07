<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statuses extends Model {
    use HasFactory;

    protected $table = "statuses";

    public const TYPES = [
        'pedido'    => 'Pedido'
    ];

    protected $fillable = ['text','type'];
}
