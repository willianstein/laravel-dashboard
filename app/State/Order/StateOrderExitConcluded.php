<?php

namespace App\State\Order;

use App\Models\OrderItemExits;
use Illuminate\Http\Request;

/**
 *
 */
class StateOrderExitConcluded extends StateOrderExit {

    /**
     * ATUALIZA DATA DE PREVISÃO
     * @param array $arguments
     * @return StateOrderExitConcluded
     */
    public function updateForecast(array $arguments) {
        return $this->back('Ação não permitida. Pedido concluído',true);
    }

    /**
     * CANCELA PEDIDO
     * @return StateOrderExitConcluded
     */
    public function cancelOrder() {
        return $this->back('Ação não permitida. Pedido concluído',true);
    }

    /**
     * ADICIONA ITEM AO PEDIDO
     * @param Request $request
     * @return StateOrderExitConcluded
     */
    public function addItem(Request $request) {
        return $this->back('Ação não permitida. Pedido concluído',true);
    }

    /**
     * REMOVE ITEM PEDIDO
     * @param OrderItemExits $orderItem
     * @return StateOrderExitConcluded
     */
    public function removeItem(OrderItemExits $orderItem) {
        return $this->back('Ação não permitida. Pedido concluído',true);
    }

    /**
     * ENVIA PARA SEPARAÇÃO
     * @return StateOrderExitConcluded
     */
    public function breakApartOrder() {
        return $this->back('Ação não permitida. Pedido concluído',true);
    }

    /**
     * ADICIONA DESTINATARIO
     * @param Request $request
     * @return StateOrderExitConcluded
     */
    public function addRecipient(Request $request) {
        return $this->back('Ação não permitida. Pedido concluído',true);
    }

    /**
     * ADICIONA TRANSPORTE
     * @param Request $request
     * @return StateOrderExitConcluded
     */
    public function addTransport(Request $request) {
        return $this->back('Ação não permitida. Pedido concluído',true);
    }

    /**
     * ENVIA PARA CONFERENCIA
     * @return StateOrderExitConcluded
     */
    public function sendToConference() {
        return $this->back('Ação não permitida. Pedido concluído',true);
    }

    /**
     * MARCA COMO CONFERIDO
     * @return StateOrderExitConcluded
     */
    public function checked() {
        return $this->back('Ação não permitida. Pedido concluído',true);
    }

    /**
     * DESABILITA A ALTERAÇÃO DO TRANSPORTE
     * @param Request $request
     * @return StateOrderExitConcluded
     */
    public function updateTransport(Request $request) {
        return $this->back('Ação não permitida. Pedido concluído',true);
    }

    /**
     * DESABILITA A CONCLUSÃO DO PEDIDO
     * @return StateOrderExitConcluded
     */
    public function complete() {
        return $this->back('O pedido já foi concluído anteriormente',true);
    }

}
