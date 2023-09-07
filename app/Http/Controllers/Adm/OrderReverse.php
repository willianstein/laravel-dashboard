<?php

namespace App\Http\Controllers\Adm;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Offices;
use App\Models\OrderEntries;

class OrderReverse extends Controller
{

    function __construct()
    {
        $this->middleware(
            'permission:pedidos-reversa|ver-pedidos-reversa|cadastrar-pedidos-reversa|inativar-pedidos-reversa|editar-pedidos-reversa|deletar-pedidos-reversa',
            ['only' => [
                'index', 'save', 'getListOrders', 'getListOrderItems',
                'new', 'manager', 'transport', 'updateForecast', 'cancel', 'receive', 'addItem', 'removeItem'
            ]]
        );
    }

    /**
     * PAGINA PRINCIPAL
     */
    public function index()
    {
        $offices = Offices::all();
        $reverse = true;
        return view('adm.orderEntry', compact('offices', 'reverse'));
    }

    /**
     * RETORNA UMA LISTA JSON COM OS PEDIDOS
     * @param Request|null $request
     * @return void
     * @throws Exception
     */
    public function getListOrders(?Request $request)
    {
        if ($orderEntries = OrderEntries::where('type', OrderEntries::type('Reversa'))
            ->where('created_at', '>', now()->subDays(30)->endOfDay())
            ->orderBy('id', 'desc')->get()
        ) {
            foreach ($orderEntries as $orderEntry) {
                $data['data'][] = [
                    date_fmt($orderEntry->created_at, 'd/m/Y H:m'),
                    $orderEntry->id,
                    $orderEntry->third_system_id,
                    $orderEntry->office->name,
                    $orderEntry->partner->name,
                    date_fmt($orderEntry->forecast, 'd/m/Y'),
                    $orderEntry->status,
                    "<a href=\"" . route('adm.orderEntry.manager', ['orderEntry' => $orderEntry->id]) . "\" class=\"badge badge-success\"><i class=\"fas fa-pen\"></i> EDITAR</a>"
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }
}
