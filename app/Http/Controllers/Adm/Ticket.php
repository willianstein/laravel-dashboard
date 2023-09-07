<?php

namespace App\Http\Controllers\Adm;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmTicketRequest;
use App\Models\Partners;
use App\Models\TicketCategories;
use App\Models\TicketMessages;
use App\Models\Tickets;
use Illuminate\Support\Facades\Auth;

class Ticket extends Controller
{

    function __construct()
    {
        $this->middleware(
            'permission:ticket|ver-ticket|cadastrar-ticket|inativar-ticket|editar-ticket|deletar-ticket',
            ['only' => ['index', 'save', 'getTickets', 'manager', 'sendMessage', 'transfer', 'comment', 'finish']]
        );
    }
    /**
     * PAGINA INICIAL
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $dropCategories = TicketCategories::get(['id', 'name'])->toArray();
        $dropPartners = Partners::orderBy('name', 'ASC')->get(['id', 'name'])->toArray();
        return view('adm.ticket', compact('dropCategories', 'dropPartners'));
    }

    /**
     * SALVA TICKET
     * @param AdmTicketRequest $request
     * @return void
     */
    public function save(AdmTicketRequest $request)
    {

        /* Informações adicionais */
        $request['requester_id'] = auth()->user()->id;
        $request['status'] = Tickets::status('Aberto');

        if (!$ticket = Tickets::create($request->toArray())) {
            echo (new Response())->error('Ops, não foi possivel criar seu ticket')->json();
            return;
        }

        if (!TicketMessages::create([
            'ticket_id' => $ticket->id,
            'requester_id' => auth()->user()->id,
            'origin' => 'solicitante',
            'message' => $request->message
        ])) {
            echo (new Response())->error('Ops, não foi possivel anexar a mensagem')->json();
            return;
        }

        echo (new Response())->success('Ticket Criado com Sucesso')->action('reloadDataTable', 'table')->json();
    }

    /**
     * LISTAR TICKETS
     * @return void
     */
    public function getTickets()
    {
        $user = Auth::user();
        /* Busca todos os Tickets */
        if ($tickets = Tickets::all()) {
            foreach ($tickets as $ticket) {
                $data['data'][] = [
                    $ticket->id,
                    $ticket->category->name,
                    $ticket->requester->name,
                    $ticket->partner->name,
                    $ticket->responsible->name ?? "ND",
                    date_fmt($ticket->created_at, 'd/m/Y H:m') . "h",
                    date_fmt($ticket->initial_care_at, 'd/m/Y H:m') ?? "--/--/----",
                    date_fmt($ticket->ended_in, 'd/m/Y H:m') ?? "--/--/----",
                    $user->hasPermissionTo('editar-ticket') ?
                    "<a href=\"" . route('adm.ticket.manager', ['ticket' => $ticket->id]) . "\" class=\"badge badge-success\"><i class=\"fas fa-pen\"></i> GERENCIAR</a>"
                    : '',
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * GERENCIAR TICKET
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function manager(Tickets $ticket)
    {

        $messages = TicketMessages::where('ticket_id', $ticket->id)->get();
        $dropResponsible = User::where('type', 'user_adm')->orderBy('name', 'asc')->get(['id', 'name'])->toArray();

        return view('adm.ticketManager', compact('messages', 'ticket', 'dropResponsible'));
    }

    /**
     * ENVIA MENSAGEM
     * @param Tickets $ticket
     * @param Request $request
     * @return void
     */
    public function sendMessage(Tickets $ticket, Request $request)
    {

        if (empty(trim($request->message))) {
            echo (new Response())->info('Escreva a mensagem antes de enviar')->json();
            return;
        }

        /* Checa Primeiro Responsavel */
        if ($ticket->responsible_id == null) {
            $ticket->responsible_id = auth()->user()->id;
            $ticket->initial_care_at = date('Y-m-d H:i:s');
            $ticket->status = Tickets::status('Em Andamento');
            $ticket->save();
        }

        /* Create Message */
        if (!TicketMessages::create([
            'ticket_id' => $ticket->id,
            'responsible_id' => auth()->user()->id,
            'origin' => 'responsavel',
            'message' => $request->message
        ])) {
            echo (new Response())->error('Ops, não foi possivel anexar a mensagem')->json();
            return;
        }

        echo (new Response())->action('reload', true)->json();
    }

    /**
     * TRANSFERE O RESPONSAVEL
     * @param Tickets $ticket
     * @param Request $request
     * @return void
     */
    public function transfer(Tickets $ticket, Request $request)
    {

        if (empty(trim($request->responsible_id))) {
            echo (new Response())->info('Selecione o Responsável')->json();
            return;
        }

        if (!$newResponsible = User::find($request->responsible_id)) {
            echo (new Response())->info('Responsável não Encontrado')->json();
            return;
        }

        TicketMessages::create([
            'ticket_id' => $ticket->id,
            'responsible_id' => auth()->user()->id,
            'origin' => 'responsavel',
            'message' => "Transferiu o ticket para o(a) {$newResponsible->name}"
        ]);

        $ticket->responsible_id = $newResponsible->id;
        $ticket->save();

        echo (new Response())->action('reload', true)->json();
    }

    /**
     * COMENTA TICKET NO PRIVADO, PARCEIRO NÃO VISUALIZA
     * @param Tickets $ticket
     * @param Request $request
     * @return void
     */
    public function comment(Tickets $ticket, Request $request)
    {

        if (empty(trim($request->message))) {
            echo (new Response())->info('Escreva a mensagem antes de enviar')->json();
            return;
        }

        TicketMessages::create([
            'ticket_id' => $ticket->id,
            'responsible_id' => auth()->user()->id,
            'origin' => 'responsavel',
            'message' => "Comentou: " . $request->message,
            'type' => 'privada'
        ]);

        echo (new Response())->action('reload', true)->json();
    }

    /**
     * FINALIA TICKET
     * @param Tickets $ticket
     * @return void
     */
    public function finish(Tickets $ticket)
    {

        if (auth()->user()->id != $ticket->responsible_id) {
            (new Response())->info("Você não pode finalizar este ticket")->flash();
            return back();
        }

        if ($ticket->status == Tickets::status('Finalizado')) {
            (new Response())->error("Ticket Finalizado Anteiormente")->flash();
            return back();
        }

        $ticket->status = Tickets::status('Finalizado');
        $ticket->ended_in = date('Y-m-d H:i:s');
        $ticket->save();

        TicketMessages::create([
            'ticket_id' => $ticket->id,
            'responsible_id' => auth()->user()->id,
            'origin' => 'responsavel',
            'message' => "Finalizou o Ticket"
        ]);

        (new Response())->success("Ticket Finalizado com Sucesso")->flash();
        return redirect()->route('adm.ticket.index');
    }
}
