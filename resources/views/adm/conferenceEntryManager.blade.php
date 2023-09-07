@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Conferência: Pedido de Entrada Nº {{ $orderEntry->id }}</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- ORDER ATTRIBUTES -->
                    <div class="col-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="pill" href="#main-data" role="tab">O
                                            Pedido</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#service-data" role="tab">Serviços</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <!-- MAIN DATA -->
                                    <div class="tab-pane fade show active" id="main-data" role="tabpanel">
                                        <form method="POST" id="myForm">
                                            <div class="form-row justify-content-end">

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="" id=""
                                                        placeholder="Unidade" value="{{ $orderEntry->office->name }}"
                                                        disabled data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="" id=""
                                                        placeholder="Parceiro" value="{{ $orderEntry->partner->name }}"
                                                        disabled data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="status" id="status" placeholder="Status"
                                                        disabled value="{{ $orderEntry->status }}" data-toggle="tooltip"
                                                        class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="date" name="forecast" id="forecast" disabled
                                                        value="{{ $orderEntry->forecast }}" placeholder="Data Prevista"
                                                        data-toggle="tooltip" class="form-control">
                                                </div>
                                                @can('concluir-conferencia-entrada')
                                                    <div class="form-group col-md-5">
                                                        <a href="{{ route('adm.conferenceEntry.checked', ['orderEntry' => $orderEntry->id]) }}"
                                                            class="btn btn-success float-right ajax-link ml-3">
                                                            <i class="fas fa-check"></i>
                                                            <span class="text-bold">Concluir Conferência</span>
                                                        </a>
                                                    </div>
                                                @endcan
                                            </div>
                                        </form>
                                    </div>

                                    <!-- SERVICES DATA -->
                                    <div class="tab-pane fade" id="service-data" role="tabpanel">
                                        <form method="post" id="serviceForm" action="{{route('adm.conferenceEntry.addService',$orderEntry)}}" class="mt-2">
                                            <div class="form-row">

                                                <div class="form-group col-md-3">
                                                    <input type="number" name="quantity" id="quantity" placeholder="Quantidade" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-7">
                                                    <?=form_select('service_id',($dropServices??null),null,['id'=>'service_id','placeholder'=>'Serviço','data-toggle'=>'tooltip','class'=>'select2'],'Selecione o Serviço')?>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    @csrf
                                                    <input type="hidden" name="partner_service_id" id="partner_service_id">
                                                    <button class="btn btn-block btn-outline-info">Salvar Dados</button>
                                                </div>

                                            </div>
                                        </form>
                                        <hr>
                                        <h5>Servicos Cadastrados</h5>
                                        <div class="row">
                                            <div class="col-12">
                                                <table id="service-table" class="table table-striped dataTable dtr-inline">
                                                    <thead>
                                                    <tr>
                                                        <th>Quantidade</th>
                                                        <th>Serviço</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ITEMS -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5 class="m-0">Itens deste pedido</h5>
                                </div>
                                <div class="card-tools"></div>
                            </div>

                            <div class="card-body">
                                <table id="item-table" class="table table-striped dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ISBN</th>
                                            <th>Titulo</th>
                                            <th>Editora</th>
                                            <th>Quantidade</th>
                                            <th>Conferido</th>
                                            <th>Descartado</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Modal -->
    <div class="modal fade" id="modal-discard">
        <div class="modal-dialog">
            <div class="modal-content modal-danger">
                <div class="modal-header">
                    <h4 class="modal-title">Descartar Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="discard-form">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <input type="text" name="quantity" id="quantity" placeholder="Quantidade"
                                    value="" data-toggle="tooltip" class="form-control">
                            </div>
                            <div class="form-group col-12">
                                <select name="stock_id" id="stock-discard-form" placeholder="Endereço do Estoque"
                                    data-toggle="tooltip" class="form-control"></select>
                            </div>
                            <div class="form-group col-12">
                                <div class="custom-file">
                                    <input type="file" name="attachment[]" id="attachment" multiple
                                        class="custom-file-input">
                                    <label class="custom-file-label" for="attachment">Anexar</label>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <textarea name="notes" placeholder="Notas" rows="8" data-toggle="tooltip" class="form-control"></textarea>
                            </div>

                            <div class="form-group col-12">
                                @csrf
                                <button class="btn btn-block btn-outline-danger float-right">
                                    <small><i class="fas fa-ban"></i></small>
                                    <span class="text-bold">DESCARTAR</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-check">
        <div class="modal-dialog">
            <div class="modal-content modal-success">
                <div class="modal-header">
                    <h4 class="modal-title">Endereçar Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="receive-form">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <input type="number" name="quantity" id="quantity" placeholder="Quantidade"
                                    value="" data-toggle="tooltip" class="form-control">
                            </div>
                            <div class="form-group col-12">
                                <select name="stock_id" id="stock-receive-form" placeholder="Endereço"
                                    data-toggle="tooltip" class="form-control"></select>
                            </div>
                            <div class="form-group col-12">
                                @csrf
                                <button class="btn btn-block btn-outline-success float-right">
                                    <small><i class="fas fa-check"></i></small>
                                    <span class="text-bold">CONFERIR E ENDEREÇAR</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/buscacep.js') }}"></script>
    <script>
        $(document).ready(function() {
            ajaxSelect2('filter_partner_id', '{{ route('adm.stock.getPartners') }}', 'Parceiro');
            ajaxSelect2('partner_id', '{{ route('adm.stock.getPartners') }}', 'Parceiro');
            ajaxSelect2('product_id', '{{ route('adm.orderItem.findProduct') }}', 'Produto');
            $("#country select").val("{{ $orderEntry->recipient->country ?? 'Brasil' }}").change();

            loadDataTable("{{route('adm.conferenceEntry.getListOrderItems',['orderEntry'=>$orderEntry->id])}}", 'item-table');
            loadDataTable("{{route('adm.conferenceEntry.getServices',['orderEntry'=>$orderEntry->id])}}", 'service-table');

            /* Carrega Endereços do Modal de Conferencia */
            $('body').on('click', '.btn-receive', function() {
                let product = $(this).data('product');
                let partner = $(this).data('partner');
                let orderItem = $(this).data('id');
                let urlDrop = '{{ route('adm.stock.getDropByPartnerAndProduct') }}' + '/' + partner + '/' +
                    product + '/normal';
                $('#receive-form').attr('action',
                    '{{ route('adm.conferenceEntry.checkItem', ['orderEntry' => $orderEntry->id]) }}/' +
                    orderItem);
                $.getJSON(urlDrop, function(data) {
                    if (data) {
                        $('#stock-receive-form').html('');
                        $('#stock-receive-form').append($('<option>', {
                            value: 0,
                            text: 'Selecione um Endereço'
                        }));
                    }
                    $.each(data, function(key, val) {
                        $('#stock-receive-form').append($('<option>', {
                            value: key,
                            text: val
                        }));
                    });
                });
            });
            /* Carrega Endereços do Modal de Conferencia */
            $('body').on('click', '.btn-discard', function() {
                let product = $(this).data('product');
                let partner = $(this).data('partner');
                let orderItem = $(this).data('id');
                let urlDrop = '{{ route('adm.stock.getDropByPartnerAndProduct') }}' + '/' + partner + '/' +
                    product + '/truncado';
                $('#discard-form').attr('action',
                    '{{ route('adm.conferenceEntry.discardItem', ['orderEntry' => $orderEntry->id]) }}/' +
                    orderItem);
                $.getJSON(urlDrop, function(data) {
                    if (data) {
                        $('#stock-discard-form').html('');
                        $('#stock-discard-form').append($('<option>', {
                            value: 0,
                            text: 'Selecione um Endereço'
                        }));
                    }
                    $.each(data, function(key, val) {
                        $('#stock-discard-form').append($('<option>', {
                            value: key,
                            text: val
                        }));
                    });
                });
            });
        });
    </script>
@endsection
