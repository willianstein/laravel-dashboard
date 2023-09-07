<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTransportTags extends Model {

    use HasFactory;

    protected $table = "order_transport_tags";

    protected $fillable = ['order_id','bill_lading','tag_code','price','delivery_time','status','metadata'];
}
