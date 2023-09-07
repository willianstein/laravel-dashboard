<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStocks extends Model {
    use HasFactory;

    protected $table = "order_stocks";

    protected $fillable = ['partner_id','order_id','order_item_id','stock_id','separate_quantity'];
}
