<?php

namespace App\Http\Controllers\Adm;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmTransportRequest;
use App\Http\Requests\AdmOrderItemRequest;
use App\Http\Requests\AdmOrderEntryRequest;
use App\State\Order\StateOrderEntryNew;
use App\Models\History\History;
use App\Models\Partners;
use App\Models\Transports;
use App\Models\Offices;
use App\Models\OrderEntries;
use App\Models\OrderItemEntries;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

/**
 *  CLASSE DE PEDIDOS DE ENTRADA
 */
class OrderEntry extends Controller {

    use Helper;


    function __construct()
    {
        $this->middleware(
            'permission:pedidos|ver-pedidos|cadastrar-pedidos|inativar-pedidos|editar-pedidos|deletar-pedidos',
            ['only' => ['index', 'save', 'getListOrders', 'getListOrderItems',
             'new', 'manager', 'transport', 'updateForecast', 'cancel', 'receive', 'addItem', 'removeItem']]
        );
    }

    /**
     * PAGINA PRINCIPAL
     */
    public function index()
    {
        $offices = Offices::all();
        return view('adm.orderEntry', compact('offices'));
    }

    /**
     * RETORNA UMA LISTA JSON COM OS PEDIDOS
     * @param Request|null $request
     * @return void
     * @throws Exception
     */
    public function getListOrders(?Request $request) {
        
          $user = Auth::user();
          
        if($orderEntries = OrderEntries::where('type',OrderEntries::type('Entrada'))
            ->where('created_at','>', now()->subDays(30)->endOfDay())
            ->orderBy('id','desc')->get()){
      
            foreach ($orderEntries as $orderEntry){
                $data['data'][] = [
                    date_fmt($orderEntry->created_at,'d/m/Y H:m'),
                    $orderEntry->id,
                    $orderEntry->third_system_id,
                    $orderEntry->office->name,
                    $orderEntry->partner->name,
                    date_fmt($orderEntry->forecast, 'd/m/Y'),
                    $orderEntry->status,
                    $user->hasPermissionTo('editar-pedidos') ?
                    "<a href=\"".route('adm.orderEntry.manager',['orderEntry'=>$orderEntry->id])."\" class=\"badge badge-success\"><i class=\"fas fa-pen\"></i> EDITAR</a>"
                    : '',
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

    /**
     *  RETORNA UMA LISTA COM OS ITENS DO PEDIDO EM JSON
     * @param OrderEntries $orderEntry
     * @return void
     */
    public function getListOrderItems(OrderEntries $orderEntry) {
        /* Busca todos os Offices */
        if($items = OrderItemEntries::where('order_id',$orderEntry->id)->get()){
            foreach ($items as $item){
                $data['data'][] = [
                    "({$item->product->isbn}) {$item->product->title}",
                    $item->quantity,
                    $item->status,
                    "<span class=\"badge badge-danger ajax-link\" data-url=\"".route('adm.orderEntry.removeItem',['orderEntry'=>$item->order_id,'orderItem'=>$item->id])."\"><small><i class=\"fas fa-ban\"></i></small> EXCLUIR</span>"
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

    /**
     * NOVO PEDIDO DE ENTRADA
     * @param AdmOrderEntryRequest $request
     * @return RedirectResponse|void
     */
    public function new(AdmOrderEntryRequest $request)
    {
        $listpost = $request->validated();
        $listpost['type'] = (empty($request->reverse)?OrderEntries::type('Entrada'):OrderEntries::type('Reversa'));
        $listpost['status'] = OrderEntries::status(StateOrderEntryNew::class);

        if(Auth::user()->type == 'user_app'){
            $listpost['partner_id'] = Auth::user()->id;
        }

        if(!$orderEntry = OrderEntries::create($listpost)){
            echo (new Response())->error('Erro ao salvar pedido')->json();
            die();
        }

        /* Histórico */
        (new History($orderEntry))->description('Ordem criada com sucesso')->save();

        echo (new Response())->success('Pedido Criado com Sucesso :)')->flash();
        return redirect()->route('adm.orderEntry.manager', ['orderEntry' => $orderEntry]);
    }

    /**
     * GERENCIA PEDIDO
     * @param OrderEntries $orderEntry
     * @return Application|Factory|View
     */
    public function manager(OrderEntries $orderEntry) {
        $dropModality = Transports::MODALITY;
        $dropPackaging = Transports::PACKAGING;
        $dropTransport = Partners::get(['id','name'])->toArray();
        return view('adm.orderEntryManager', compact('orderEntry','dropModality','dropPackaging','dropTransport'));
    }

    /**
     * ATUALIZA INFORMAÇÕES DO TRANSPORTE
     * @param OrderEntries $orderEntry
     * @param AdmTransportRequest $request
     * @return void
     */
    public function transport(OrderEntries $orderEntry, AdmTransportRequest $request) {
        $listPost = $request->validated();

        if($transport = Transports::updateOrCreate(['id'=>$listPost['transport_id']],$listPost)){
            $orderEntry->transport_id = $transport->id;
            $orderEntry->save();
            echo (new Response())->success('Transporte Salvo com Sucesso')->json();
        }

    }

    /**
     * ATUALIZA DATA PREVISTA
     * @param OrderEntries $orderEntry
     * @param Request $request
     * @return void
     */
    public function updateForecast(OrderEntries $orderEntry, Request $request) {
        $request['forecast'] = filter_var($request['forecast'], FILTER_SANITIZE_STRIPPED);
        $stateOrderEntry = $orderEntry->handle('updateForecast',$request->toArray());
        if($stateOrderEntry->isFail()){
            echo (new Response())->error($stateOrderEntry->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderEntry->getMessage())->json();
    }

    /**
     * CANCELA PEDIDO
     * @param OrderEntries $orderEntry
     * @return void
     */
    public function cancel(OrderEntries $orderEntry) {
        $stateOrderEntry = $orderEntry->handle('cancelOrder');
        if($stateOrderEntry->isFail()){
            echo (new Response())->error($stateOrderEntry->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderEntry->getMessage())->action('loadForm','myForm')->data($stateOrderEntry->getOrderEntry()->toArray())->json();
    }

    /**
     * AGUARDA RECEBIMENTO
     * @param OrderEntries $orderEntry
     * @return void
     */
    public function receive(OrderEntries $orderEntry) {
        $stateOrderEntry = $orderEntry->handle('receiveOrder');
        if($stateOrderEntry->isFail()){
            echo (new Response())->error($stateOrderEntry->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderEntry->getMessage())->action('loadForm','myForm')->data($stateOrderEntry->getOrderEntry()->toArray())->json();
    }

    /**
     * ADICIONA ITEM NO PEDIDO
     * @param OrderEntries $orderEntry
     * @param AdmOrderItemRequest $request
     * @return void
     */
    public function addItem(OrderEntries $orderEntry, AdmOrderItemRequest $request) {
        $request = $request->validated();
        $stateOrderEntry = $orderEntry->handle('addItem',$request);
        if($stateOrderEntry->isFail()){
            echo (new Response())->error($stateOrderEntry->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderEntry->getMessage())->action('reloadDataTable','item-table')->json();
    }

    /**
     * REMOVE ITEM NO PEDIDO
     * @param OrderEntries $orderEntry
     * @param OrderItemEntries $orderItem
     * @return void
     */
    public function removeItem(OrderEntries $orderEntry, OrderItemEntries $orderItem) {
        $stateOrderEntry = $orderEntry->handle('removeItem',$orderItem);
        if($stateOrderEntry->isFail()){
            echo (new Response())->error($stateOrderEntry->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderEntry->getMessage())->action('reloadDataTable','item-table')->json();
    }

}
