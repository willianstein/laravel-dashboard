<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\State\OrderItems\StateOrderExitItemChecked;
use App\State\OrderItems\StateOrderExitItemComplete;
use App\State\OrderItems\StateOrderExitItemConference;
use App\State\OrderItems\StateOrderExitItemSeparate;
use App\State\OrderItems\StateOrderExitItemSeparation;
use App\State\OrderItems\StateOrderExitItemTransport;
use App\State\OrderItems\StateOrderExitItemNew;

class OrderItemExits extends OrderItems {
    use HasFactory;

    /**
     * STATUS DO ITEM
     */
    public const STATUSES = [
        'Novo'                  => StateOrderExitItemNew::class,
        'Em Separação'          => StateOrderExitItemSeparation::class,
        'Separado'              => StateOrderExitItemSeparate::class,
        'Em Conferência'        => StateOrderExitItemConference::class,
        'Conferido'             => StateOrderExitItemChecked::class,
        'Aguardando Transporte' => StateOrderExitItemTransport::class,
        'Concluído'             => StateOrderExitItemComplete::class
    ];

    /**
     * RETORNA O STATUS DO PEDIDO
     * @param string $estate
     * @return string|null
     */
    public static function status(string $estate):? string {
        return (array_search($estate,self::STATUSES)??null);
    }

    /**
     * MANIPULA AS AÇÕES DOS BOTÕES.
     * @param string $action
     * @param ...$arguments
     * @return mixed
     */
    public function handle(string $action,...$arguments) {
        $class = self::STATUSES[$this->status];
        return (new $class($this))->$action(...$arguments);
    }

    /**
     * RETORNA PEDIDO
     * @return void
     */
    public function order() {
        return $this->belongsTo(OrderEntries::class, 'order_id','id');
    }
}
