<?php

namespace App\Http\Controllers\Adm;

use App\Http\Requests\AdmRecipientRequest;
use App\Http\Requests\AdmTransportRequest;
use App\Models\OrderItemExits;
use App\Services\Integrations\GetNfe;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmOrderExitRequest;
use App\Models\MovementStatus;
use App\State\Order\StateOrderExitNew;

use App\Models\OrderItemEntries;
use App\Models\Offices;
use App\Models\OrderExits;
use App\Models\Partners;
use App\Models\Transports;

class OrderExit extends Controller
{

    function __construct()
    {
        $this->middleware(
            'permission:pedidos-saida|ver-pedidos-saida|cadastrar-pedidos-saida|inativar-pedidos-saida|editar-pedidos-saida|deletar-pedidos-saida',
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
        return view('adm.orderExit', compact('offices'));
    }

    /**
     * CRIA UM NOVO PEDIDO
     * @param AdmOrderExitRequest $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function new(AdmOrderExitRequest $request)
    {
        $listpost = $request->validated();
        $listpost['type'] = OrderExits::type('Saída');
        $listpost['status'] = OrderExits::status(StateOrderExitNew::class);
        if (!$orderExit = OrderExits::create($listpost)) {
            echo (new Response())->error('Erro ao salvar pedido')->json();
            die();
        }

          /* Histórico */
          (new History($orderExit))->description($orderExit::status(StateOrderExitNew::class))->save();


        echo (new Response())->success('Pedido Criado com Sucesso :)')->flash();
        return redirect()->route('adm.orderExit.manager', ['orderExit' => $orderExit]);
    }

    /**
     * RETORNA UMA LISTA JSON COM OS PEDIDOS
     * @return void
     * @throws \Exception
     */
    public function getListOrders()
    {
        if ($orderExits = OrderExits::where('type', OrderExits::type('Saída'))
            ->where('created_at', '>', now()->subDays(30)->endOfDay())
            ->orderBy('id', 'desc')->get()
        ) {
            foreach ($orderExits as $orderExit) {
                $data['data'][] = [
                    date_fmt($orderExit->created_at, 'd/m/Y H:m'),
                    $orderExit->id,
                    $orderExit->third_system_id,
                    $orderExit->invoice_number,
                    $orderExit->office->name,
                    $orderExit->partner->name,
                    date_fmt($orderExit->forecast, 'd/m/Y'),
                    $orderExit->status,
                    "<a href=\"" . route('adm.orderExit.manager', ['orderExit' => $orderExit->id]) . "\" class=\"badge badge-success\"><i class=\"fas fa-pen\"></i> EDITAR</a>"
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * GERENCIA PEDIDO
     * @param OrderExits $orderExit
     * @return Application|Factory|View
     */
    public function manager(OrderExits $orderExit)
    {
        $dropModality = Transports::MODALITY;
        $dropPackaging = Transports::PACKAGING;
        $dropTransport = Partners::get(['id', 'name'])->toArray();
        return view('adm.orderExitManager', compact('orderExit', 'dropModality', 'dropPackaging', 'dropTransport'));
    }

    /**
     *  RETORNA UMA LISTA COM OS ITENS DO PEDIDO EM JSON
     * @param OrderExits $orderExit
     * @return void
     */
    public function getListOrderItems(OrderExits $orderExit)
    {
        /* Busca todos os Offices */
        if ($items = OrderItemEntries::where('order_id', $orderExit->id)->get()) {
            foreach ($items as $item) {
                $data['data'][] = [
                    "({$item->product->isbn}) {$item->product->title}",
                    $item->quantity,
                    $item->status,
                    "<span class=\"badge badge-danger ajax-link\" data-url=\"" . route('adm.orderExit.removeItem', ['orderExit' => $item->order_id, 'orderItem' => $item->id]) . "\"><small><i class=\"fas fa-ban\"></i></small> EXCLUIR</span>"
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }


    /**
     * ATUALIZA DATA DE PREVISAO
     * @param OrderExits $orderExit
     * @param Request $request
     * @return void
     */
    public function updateForecast(OrderExits $orderExit, Request $request)
    {
        $request['forecast'] = filter_var($request['forecast'], FILTER_SANITIZE_STRIPPED);
        $stateOrderEntry = $orderExit->handle('updateForecast', $request->toArray());
        if ($stateOrderEntry->isFail()) {
            echo (new Response())->error($stateOrderEntry->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderEntry->getMessage())->json();
    }

    /**
     * CANCELA PEDIDO DE SAIDA
     * @param OrderExits $orderExit
     * @return void
     */
    public function cancel(OrderExits $orderExit)
    {
        $stateOrderExit = $orderExit->handle('cancelOrder');
        if ($stateOrderExit->isFail()) {
            echo (new Response())->error($stateOrderExit->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderExit->getMessage())->action('loadForm', 'myForm')->data($stateOrderExit->getOrderExit()->toArray())->json();
    }

    /**
     * ADICIONA ITEM DO PEDIDO
     * @param OrderExits $orderExit
     * @param Request $request
     * @return void
     */
    public function addItem(OrderExits $orderExit, Request $request)
    {
        $stateOrderExit = $orderExit->handle('addItem', $request);
        if ($stateOrderExit->isFail()) {
            echo (new Response())->error($stateOrderExit->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderExit->getMessage())->action('reloadDataTable', 'item-table')->json();
    }

    /**
     * REMOVE ITEM DO PEDIDO
     * @param OrderExits $orderExit
     * @param OrderItemExits $orderItem
     * @return void
     */
    public function removeItem(OrderExits $orderExit, OrderItemExits $orderItem)
    {
        $stateOrderExit = $orderExit->handle('removeItem', $orderItem);
        if ($stateOrderExit->isFail()) {
            echo (new Response())->error($stateOrderExit->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderExit->getMessage())->action('reloadDataTable', 'item-table')->json();
    }

    /**
     * ADICIONAR DESTINATARIO
     * @param OrderExits $orderExit
     * @param AdmRecipientRequest $request
     * @return void
     */
    public function recipient(OrderExits $orderExit, AdmRecipientRequest $request)
    {
        $request->validated();
        $stateOrderExit = $orderExit->handle('addRecipient', $request);
        if ($stateOrderExit->isFail()) {
            echo (new Response())->error($stateOrderExit->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderExit->getMessage())->json();
    }

    /**
     * ADICIONA TRANSPORTE
     * @param OrderExits $orderExit
     * @param AdmTransportRequest $request
     * @return void
     */
    public function transport(OrderExits $orderExit, AdmTransportRequest $request)
    {
        $stateOrderExit = $orderExit->handle('addTransport', $request);
        if ($stateOrderExit->isFail()) {
            echo (new Response())->error($stateOrderExit->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderExit->getMessage())->json();
    }

    /**
     * ENVIAR PARA SEPARAÇÃO
     * @param OrderExits $orderExit
     * @return void
     */
    public function breakApart(OrderExits $orderExit)
    {
        $stateOrderExit = $orderExit->handle('breakApartOrder');

        if ($stateOrderExit->isFail()) {
            echo (new Response())->error($stateOrderExit->getMessage())->json();
            return;
        }

        echo (new Response())->success($stateOrderExit->getMessage())->action('loadForm', 'myForm')->data($stateOrderExit->getOrderExit()->toArray())->json();
    }

    /**
     * ADICIONAR UM XML
     * @param OrderExits $orderExit
     * @param Request $request
     * @return void
     */
    public function addNfe(OrderExits $orderExit, Request $request)
    {
        $stateOrderExit = $orderExit->handle('addNfe', $request);
        if ($stateOrderExit->isFail()) {
            echo (new Response())->error($stateOrderExit->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderExit->getMessage())->action('reload', true)->json();
    }


    public function getNfeXml(OrderExits $orderExit)
    {

        GetNfe::order($orderExit);
    }
}
