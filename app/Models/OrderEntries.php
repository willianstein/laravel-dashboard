<?php

namespace App\Models;

use App\State\Order\StateOrderEntryNew;
use App\State\Order\StateOrderEntryReceive;
use App\State\Order\StateOrderEntryConference;
use App\State\Order\StateOrderEntryReceived;
use App\State\Order\StateOrderEntryConcluded;
use App\State\Order\StateOrderEntryCanceled;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
/**
 *  MODEL PEDIDO DE ENTRADA
 */
class OrderEntries extends Orders {

    use Helper;


    /**
     * STATUS DO PEDIDO
     */
    public const STATUSES = [
        'Novo'                          => StateOrderEntryNew::class,
        'Aguardando Recebimento'        => StateOrderEntryReceive::class,
        'Aguardando Conferência'        => StateOrderEntryConference::class,
        'Recebido'                      => StateOrderEntryReceived::class,
        'Concluído'                     => StateOrderEntryConcluded::class,
        'Cancelado'                     => StateOrderEntryCanceled::class
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
     * RETORNA SE ALGUM ITEM ESTA COM UM STATUS EM ESPECIFICO
     * @param string $status
     * @return bool
     */
    public function hasStatusItems(string $status): bool {
        if(OrderItemEntries::where('order_id',$this->id)->where('status',$status)->first()){
            return true;
        }
        return false;
    }

    /**
     * RETORNA OS ITEMS DO PEDIDO
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items() {
        return $this->hasMany(OrderItemEntries::class,'order_id','id');
    }

    /**
     * RETORNA OS SERVIÇOS DO PEDIDO
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services() {
        return $this->hasMany(OrderServices::class,'order_id','id');
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
     * scopes
     */

     public function scopeWherePartner($query)
    {
        if (Helper::isAdmin()) {
            return $query;
        } else {
            return $query->where('partner_id', Auth::user()->id);
        }
    }

}
