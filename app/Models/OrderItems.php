<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *  MODEL ABSTRATA DOS ITENS DO PEDIDO
 */
abstract class OrderItems extends Model {
    use HasFactory;

    /**
     * NOME DA TABELA
     * @var string
     */
    protected $table = "order_items";

    /**
     * CONFIGURAÇÃO DOS FILLABLES DO LARAVEL
     * @var string[]
     */
    protected $fillable = [
        'quantity','product_id','isbn','order_id','status'
    ];

    /**
     * RETORNA O PRODUTO DO ITEM
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product() {
        return $this->hasOne(Products::class, 'id','product_id');
    }
}
