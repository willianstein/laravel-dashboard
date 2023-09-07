<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemAttachments extends Model {
    use HasFactory;

    protected $table = "order_item_attachments";

    protected $fillable = ['order_id','order_item_id','attachment'];
}
