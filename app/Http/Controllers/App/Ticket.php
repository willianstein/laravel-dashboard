<?php

namespace App\Http\Controllers\App;

use App\Http\Libraries\Response;
use App\Models\Partners;
use App\Models\TicketMessages;
use App\Models\Tickets;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppTicketRequest;
use App\Models\TicketCategories;

class Ticket extends Controller {

    /**
     * LISTA OS TICKETS
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index() {

        $dropCategories = TicketCategories::get(['id','name'])->toArray();
        $dropPartners = Partners::get(['id','name'])->toArray();

        return view('app.ticket', compact('dropCategories','dropPartners'));
    }

    /**
     * SALVA TICKET
     * @param AppTicketRequest $request
     * @return void
     */
    public function save(AppTicketRequest $request) {

        /* Informações adicionais */
        $request['requester_id'] = auth()->user()->id;
        $request['status'] = Tickets::status('Aberto');

        if(!$ticket = Tickets::create($request->toArray())){
            echo (new Response())->error('Ops, não foi possivel criar seu ticket')->json();
            return;
        }

        if(!TicketMessages::create([
            'ticket_id' => $ticket->id,
            'requester_id' => auth()->user()->id,
            'origin' => 'solicitante',
            'message' => $request->message
        ])){
            echo (new Response())->error('Ops, não foi possivel anexar a mensagem')->json();
            return;
        }

        echo (new Response())->success('Ticket Criado com Sucesso')->action('reloadDataTable','table')->json();

    }

    /**
     * BUSCA TODOS OS TICKETS
     * @return void
     * @throws \Exception
     */
    public function getTickets() {
        /* Busca todos os Tickets */
        if($tickets = Tickets::all()){
            foreach ($tickets as $ticket){
                $data['data'][] = [
                    $ticket->id,
                    $ticket->category->name,
                    $ticket->requester->name,
                    $ticket->partner->name,
                    $ticket->responsible->name??"ND",
                    date_fmt($ticket->created_at,'d/m/Y H:m')."h",
                    date_fmt($ticket->initial_care_at,'d/m/Y H:m')??"--/--/----",
                    date_fmt($ticket->ended_in,'d/m/Y H:m')??"--/--/----",
                    "<a href=\"".route('app.ticket.manager',['ticket'=>$ticket->id])."\" class=\"badge badge-success\"><i class=\"fas fa-pen\"></i> GERENCIAR</a>"
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

    /**
     * GERENCIAR TICKET
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function manager(Tickets $ticket) {

        $messages = TicketMessages::where('ticket_id',$ticket->id)->where('type','publico')->get();
        return view('app.ticketManager', compact('messages','ticket'));

    }

    /**
     * ENVIA MENSAGEM
     * @param Tickets $ticket
     * @param Request $request
     * @return void
     */
    public function sendMessage(Tickets $ticket, Request $request) {

        if(empty(trim($request->message))){
            echo (new Response())->info('Escreva a mensagem antes de enviar')->json();
            return;
        }

        /* Create Message */
        if(!TicketMessages::create([
            'ticket_id' => $ticket->id,
            'requester_id' => auth()->user()->id,
            'origin' => 'solicitante',
            'message' => $request->message
        ])){
            echo (new Response())->error('Ops, não foi possivel anexar a mensagem')->json();
            return;
        }

        echo (new Response())->action('reload',true)->json();

    }

}
