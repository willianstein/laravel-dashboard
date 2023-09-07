<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Histories extends Model {
    use HasFactory;

    protected $table = "histories";

    protected $fillable = [
        'user_id','entity_id','entity','description','notes','link','link_type'
    ];

    public const LINKTYPES = [
        'Interno'   => 'Interno',
        'Externo'   => 'Externo'
    ];
}
