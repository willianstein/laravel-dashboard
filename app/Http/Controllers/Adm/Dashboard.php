<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Models\Histories;
use App\Models\History\History;
use App\Models\OrderEntries;
use App\Models\OrderExits;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\User;
use App\State\Order\StateOrderExitCanceled;
use App\State\Order\StateOrderExitConcluded;
use App\State\Order\StateOrderExitConference;
use App\State\Order\StateOrderExitNew;
use App\State\Order\StateOrderExitSeparation;
use App\State\Order\StateOrderExitTransfer;
use App\State\Order\StateOrderExitTransit;
use App\State\Order\StateOrderExitTransport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class Dashboard extends Controller
{



    public function index()
    {
        $orders = Orders::all();

        //movimentacoes igual a todos pedidos menos os aguardadnco Aguardando Transporte
        $data    = date('Y-m-d 23:59:59');
        $dataFim = date('Y-m-d H:i:s', strtotime(now() . ' - 3 days'));


        $tranport = $orders->where('type', 'saida')
            ->where('status', 'Aguardando Transporte')
            ->whereBetween('created_at', [$dataFim, $data])
            ->count();

        $confere   = $orders->where('type', 'saida')->where('status', 'Aguardando Conferência')->count();
        $conclu    = $orders->where('type', 'saida')->where('status', 'Concluído')->count();
        $cancel    = $orders->where('type', 'saida')->where('status', 'Cancelado')->whereBetween('created_at', [$dataFim, $data])->count();
        $atrasados = $orders->where('type', 'saida')->where('status', 'Aguardando Transporte')->where('created_at', '<', $dataFim)->count();


        $movimentacoes  = $orders->where('type', 'saida')
            ->whereIn('status', ['Novo', 'Aguardando Separação', 'Aguardando Conferência', 'Em Separação', 'Separado', 'Em Conferência', 'Conferido'])
            ->whereBetween('created_at', [$dataFim, $data])
            ->count();


        $visitorTraffic = Orders::whereBetween('created_at', ['2023-09-04 00:00:00', '2023-09-04 23:59:59'])
            ->where('type', 'saida')
            ->groupBy('time')
            ->get(array(
                DB::raw("DATE_FORMAT(created_at, '%H:00') time"),
                DB::raw('COUNT(*) as "entradadepedido"'),
                DB::raw('6 as "entradaexpedicao"'),
                DB::raw('5 as "coletado"'),
                DB::raw('3 as "divergentes"'),
                DB::raw('2 as "atrasados"'),
            ))->toArray();


        $visitorTraffic =  json_encode($visitorTraffic);




        $userData = User::select(\DB::raw("COUNT(*) as count"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(\DB::raw("Month(created_at)"))
                    ->pluck('count');



        return view('adm.dashboard', compact('tranport', 'confere', 'conclu', 'cancel', 'atrasados', 'movimentacoes', 'visitorTraffic', 'userData'));
    }


    public function getBanner(Request $request)
    {
        $data    = date('2023-09-04 00:00:00');
        $dataFim = date('2023-09-04 23:59:59');


        // 'Novo'                      => StateOrderExitNew::class,
        // // 'Cancelado'                 => StateOrderExitCanceled::class,
        // // 'Aguardando Transferência'  => StateOrderExitTransfer::class,
        // // 'Aguardando Separação'      => StateOrderExitSeparation::class,
        // // 'Aguardando Conferência'    => StateOrderExitConference::class,
        // // 'Aguardando Transporte'     => StateOrderExitTransport::class,
        // // 'Em Transito'               => StateOrderExitTransit::class,
        // // 'Concluído'                 => StateOrderExitConcluded::class


        // dd($his );

        $visitorTraffic = Orders::whereBetween('created_at', [$data, $dataFim])
            ->where('type', 'saida')
            ->groupBy('time')
            ->get(array(
                DB::raw("DATE_FORMAT(created_at, '%H:00') time"),
                DB::raw('COUNT(*) as "entradadepedido"'),
                DB::raw('6 as "entradaexpedicao"'),
                DB::raw('5 as "coletado"'),
                DB::raw('3 as "divergentes"'),
                DB::raw('2 as "atrasados"'),
            ));

        return $visitorTraffic;


        // $er =  OrderExits::status(StateOrderExitNew::class);;
        // dd($er);

        // $orders = Orders::whereBetween('created_at', [$data, $dataFim])
        //     ->select('browser', DB::raw('count(*) as total'))
        //     ->where('type', 'saida')->get()->groupBy('created_at');


        // $orders = Orders::whereBetween('created_at', [$data, $dataFim])->selectRaw("COUNT(*) views, DATE_FORMAT(created_at, '%H:%i') date")
        //     ->groupBy('created_at')
        //     ->get();


        // dd($orders);

        // dd($orders);
    }

    public function getBannerData(Request $request)
    {
        $data    = date('2023-09-04 00:00:00');
        $dataFim = date('2023-09-04 23:59:59');

        $visitorTraffic = Orders::whereBetween('created_at', [$data, $dataFim])
            ->where('type', 'saida')
            ->groupBy('country')
            ->get(array(
                DB::raw("DATE_FORMAT(created_at, '%H:00') country"),
                DB::raw('COUNT(*) as "value"'),

            ));

        return $visitorTraffic;


        // $er =  OrderExits::status(StateOrderExitNew::class);;
        // dd($er);

        // $orders = Orders::whereBetween('created_at', [$data, $dataFim])
        //     ->select('browser', DB::raw('count(*) as total'))
        //     ->where('type', 'saida')->get()->groupBy('created_at');


        // $orders = Orders::whereBetween('created_at', [$data, $dataFim])->selectRaw("COUNT(*) views, DATE_FORMAT(created_at, '%H:%i') date")
        //     ->groupBy('created_at')
        //     ->get();


        // dd($orders);

        // dd($orders);
    }
}
