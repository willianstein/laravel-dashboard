<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model {
    use HasFactory;

    protected $table = "addresses";

    protected $fillable = [
        'partner_id','type','address','number','complement','neighborhood','city','state','country','postal_code','active'
    ];
}
