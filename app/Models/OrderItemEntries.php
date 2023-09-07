<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\State\OrderItems\StateOrderEntryItemChecked;
use App\State\OrderItems\StateOrderEntryItemConcluded;
use App\State\OrderItems\StateOrderEntryItemConference;
use App\State\OrderItems\StateOrderEntryItemNew;
use App\State\OrderItems\StateOrderEntryItemReceive;
use App\State\OrderItems\StateOrderEntryItemReceived;
use App\State\OrderItems\StateOrderEntryItemRefused;
use App\State\OrderItems\StateOrderEntryItemSeparate;
use App\State\OrderItems\StateOrderEntryItemSeparated;


class OrderItemEntries extends OrderItems {
    use HasFactory;

    /**
     * STATUS DO ITEM
     */
    public const STATUSES = [
        'Novo'              => StateOrderEntryItemNew::class,
        'Em Recebimento'    => StateOrderEntryItemReceive::class,
        'Recebido'          => StateOrderEntryItemReceived::class,
        'Recusado'          => StateOrderEntryItemRefused::class,
        'Em Separação'      => StateOrderEntryItemSeparate::class,
        'Separado'          => StateOrderEntryItemSeparated::class,
        'Em Conferência'    => StateOrderEntryItemConference::class,
        'Conferido'         => StateOrderEntryItemChecked::class,
        'Concluído'         => StateOrderEntryItemConcluded::class
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
