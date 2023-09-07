<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportRanges extends Model {

    use HasFactory;
    protected $table = 'transport_ranges';
    protected $fillable = ['name','range_from','range_up_to'];

}
