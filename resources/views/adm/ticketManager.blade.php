@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Gerenciar Ticket N⁰ {{$ticket->id}}</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-primary card-outline direct-chat direct-chat-info">
                            <div class="card-header">
                                <h3 class="card-title">Mensagens</h3>
                            </div>

                            <div class="card-body p-2">
                                <div class="direct-chat-messages">
                                    <?php
                                        foreach ($messages as $message):

                                        if($message->origin == "solicitante"):
                                            $author = $message->requester;
                                        endif;

                                        if($message->origin == "responsavel"):
                                            $author = $message->responsible;
                                            $float = "right";
                                            $floatName = "float-right";
                                            $floatDate = "float-left";
                                        else:
                                            $float = null;
                                            $floatName = "float-left";
                                            $floatDate = "float-right";
                                        endif;

                                        if($message->type == "privada"):
                                            $bgMsg = "bg-transparent";
                                            $bdMsg = "border-info";
                                        else:
                                            $bgMsg = null;
                                            $bdMsg = null;
                                        endif;
                                    ?>
                                    <div class="direct-chat-msg {{$float}}">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name {{$floatName??"float-left"}}">{{$author->name}}</span>
                                            <span class="direct-chat-timestamp {{$floatDate??"float-right"}}">{{date_fmt($message->created_at,'d/m/Y H:m')}}</span>
                                        </div>

                                        <img class="direct-chat-img" src="{{($author->photo?storage($author->photo,'public'):asset('img/profile.png'))}}">

                                        <div class="direct-chat-text {{$bdMsg}} {{$bgMsg}}">
                                            {{$message->message}}
                                        </div>
                                    </div>
                                    <?php
                                        endforeach;
                                    ?>
                                </div>
                            </div>
                            <div class="card-footer">
                                <form action="{{route('adm.ticket.sendMessage',$ticket)}}" method="post">
                                    <div class="input-group">
                                        <input type="text" name="message" placeholder="Escreva aqui sua mensagem..." class="form-control">
                                        <span class="input-group-append">
                                            @csrf
                                            <button class="btn btn-info">Enviar</button>
                                        </span>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h5 class="m-0 card-title">Dados do Ticket</h5>
                                        <div class="card-tools">
                                            <a href="{{route('adm.ticket.finish',$ticket)}}" class="badge badge-danger" style="font-size: 1em;">
                                                <i class="fas fa-times"></i>
                                                FINALIZAR TICKET
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <p><b>Solicitante:</b><br>{{$ticket->requester->name}}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><b>Parceiro:</b><br>{{$ticket->partner->name}}</p>
                                            </div>
                                            <div class="col-4">
                                                <p><b>Aberto em:</b><br>{{date_fmt($ticket->created_at,'d/m/Y H:i')}}</p>
                                            </div>
                                            <div class="col-4">
                                                <p><b>Atendido em:</b><br>{{date_fmt($ticket->initial_care_at,'d/m/Y H:i')}}</p>
                                            </div>
                                            <div class="col-4">
                                                <p><b>Finalizado em:</b><br>{{date_fmt($ticket->ended_in,'d/m/Y H:i')??"--/--/----"}}</p>
                                            </div>
                                            <div class="col-4">
                                                <p><b>Responsável:</b><br>{{$ticket->responsible->name??"----"}}</p>
                                            </div>
                                            <div class="col-4">
                                                <p><b>Categoria:</b><br>{{$ticket->category->name}}</p>
                                            </div>
                                            <div class="col-4">
                                                <p><b>Status:</b><br>{{$ticket::STATUSES[$ticket->status]}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h5 class="m-0">Transferir Atendimento</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{route('adm.ticket.transfer',$ticket)}}" method="post">
                                            <div class="form-row">
                                                <div class="form-group col-8">
                                                    <?=form_select('responsible_id',($dropResponsible??null),null,['class'=>'custom-select'],'Novo Responsável');?>
                                                </div>
                                                <div class="form-group col-4">
                                                    @csrf
                                                    <button class="btn btn-info btn-block">Transferir</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h5 class="m-0">Comentar no Chat</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{route('adm.ticket.comment',$ticket)}}" method="post">
                                            <div class="form-row justify-content-end">
                                                <div class="form-group col-12">
                                                    <textarea name="message" id="message" rows="2" class="form-control"></textarea>
                                                </div>
                                                <div class="form-group col-4">
                                                    @csrf
                                                    <button class="btn btn-info btn-block">Comentar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
@endsection
