<?php

namespace App\Services;

use App\Models\Integrations;
use App\Models\OrderExits;
use App\Models\OrderItemExits;
use App\Models\Stocks;
use Illuminate\Database\Eloquent\Collection;

class OrderService {

    public function sortOrderItemsByAddressing($orderExit, $order="DESC"): array {

        foreach ($orderExit->items as $orderItem){

            /* Procura Posições no Estoque */
            $stocks = $this->getStocks($orderExit, $orderItem);
            /* Checa Existencia */
            if($stocks->isEmpty()){
                $separated[9999][] = (object) [
                    'stock_id'      => 0,
                    'addressing'    => 'NÃO ENDEREÇADO',
                    'isbn'          => $orderItem->isbn,
                    'title'         => $orderItem->product->title,
                    'publisher'     => $orderItem->product->publisher,
                    'quantity'      => $orderItem->quantity,
                    'separated'     => 0,
                    'available'     => 0,
                    'status'        => "ND"
                ];
                continue;
            }
            /* Checa Quantidade */
            if(($orderItem->separated + $stocks->sum('quantity')) < $orderItem->quantity){
                $separated[9999][] = (object) [
                    'stock_id'      => 0,
                    'addressing'    => 'QUANTIDADE INSUFICIENTE',
                    'isbn'          => $orderItem->isbn,
                    'title'         => $orderItem->product->title,
                    'publisher'     => $orderItem->product->publisher,
                    'quantity'      => $orderItem->quantity,
                    'separated'     => 0,
                    'available'     => ($orderItem->separated + $stocks->sum('quantity')),
                    'status'        => "ND"
                ];
                continue;
            }

            /* Inclui Itens a Separar */
            $relatedTotal = 0;
            foreach ($stocks as $stock){
                $remainingAmount = $orderItem->quantity - $orderItem->separated;
                $separatedAmount = ($stock->separated($orderItem)->separate_quantity ?? 0);

                if(($stock->quantity + $relatedTotal) < $remainingAmount) {
                    $separateInThisAddress = $stock->quantity;
                    $relatedTotal += $stock->quantity;
                } else {
                    $separateInThisAddress = $remainingAmount - $relatedTotal;
                    $relatedTotal += $remainingAmount - $relatedTotal;
                }

                $separated[$stock->addressing->distance][] = (object) [
                    'stock_id'      => $stock->id,
                    'addressing'    => $stock->addressing->name,
                    'isbn'          => $orderItem->isbn,
                    'title'         => $orderItem->product->title,
                    'publisher'     => $orderItem->product->publisher,
                    'quantity'      => $separateInThisAddress,
                    'separated'     => $separatedAmount,
                    'available'     => $stock->quantity,
                    'status'        => $orderItem->status
                ];

                /* Verifica Se Já Separarou Tudo */
                if($relatedTotal >= $orderItem->quantity){
                    break;
                }

            }

        }
        /* Ordena a lista do mais distante para o mais próximo */
        if (strtoupper($order) == "DESC"){ krsort($separated); }
        /* Ordena a lista do mais próximo para o mais distante */
        if (strtoupper($order) == "ASC"){ ksort($separated); }
        return $separated;
    }

    public function sortOrderItemsByAddressingConvertedSimpleArray($orderExit) {
        $itemsByAddressing = $this->sortOrderItemsByAddressing($orderExit);
        $convertedItems = [];
        foreach ($itemsByAddressing as $orderItem){
            foreach ($orderItem as $addressing) {
                $convertedItems[] = $addressing;
            }
        }
        return $convertedItems;
    }

    /**
     * RETORNA TODAS AS POSIÇÕES DE ESTOQUE DO ITEM
     * @param OrderExits $orderExit
     * @param OrderItemExits $orderItem
     * @return Collection
     */
    private function getStocks(OrderExits $orderExit, OrderItemExits $orderItem): Collection {
        return Stocks::where('stocks.office_id', $orderExit->office_id)
            ->where('partner_id', $orderExit->partner_id)
            ->where('product_id', $orderItem->product_id)
            ->where('type', 'normal')
            ->get();
    }

}
