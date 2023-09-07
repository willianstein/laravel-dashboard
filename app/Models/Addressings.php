<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addressings extends Model {
    use HasFactory;

    protected $table = "addressings";

    protected $fillable = [
        'office_id', 'name','distance','active'
    ];

    public function office() {
        return $this->belongsTo(Offices::class);
    }

    public function stock() {
        return $this->hasOne(Stocks::class);
    }
}
