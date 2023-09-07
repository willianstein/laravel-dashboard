<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipients extends Model
{
    use HasFactory;

    protected $table = "recipients";

    protected $fillable = [
        'name', 'document01', 'postal_code', 'address', 'number', 'complement', 'neighborhood', 'city', 'state', 'country'
    ];
}
