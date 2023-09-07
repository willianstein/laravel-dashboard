<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnersRanges extends Model {

    use HasFactory;

    protected $table = 'partners_ranges';

    protected $fillable = ['partner_id','transport_range_id'];

    public function partner() {
        return $this->belongsTo(Partners::class);
    }

    public function transportRange() {
        return $this->belongsTo(TransportRanges::class);
    }

}
