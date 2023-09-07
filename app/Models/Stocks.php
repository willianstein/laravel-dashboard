<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model {
    use HasFactory;

    protected $table = "stocks";

    protected $fillable = [
        'type','office_id','partner_id','product_id','addressing_id','quantity','quantity_min','quantity_max',
        'third_party_system','third_party_system_code'
    ];

    public const TYPES = [
        'normal' => 'Normal',
        'truncado' => 'Truncado'
    ];

    public static function type(string $type):? string {
        return (array_search($type,self::TYPES)??null);
    }

    public function office() {
        return $this->belongsTo(Offices::class);
    }

    public function partner() {
        return $this->belongsTo(Partners::class);
    }

    public function product() {
        return $this->belongsTo(Products::class);
    }

    public function addressing() {
        return $this->belongsTo(Addressings::class, 'addressing_id','id');
    }

    /**
     * Retorna a Quantidade Retirada de um Item de um Determinado Endereço
     * @param OrderItems $orderItem
     * @return ?Model
     */
    public function separated(OrderItems $orderItem): ?Model {
        return OrderStocks::where('order_item_id',$orderItem->id)->where('stock_id',$this->id)->first();
    }
}
