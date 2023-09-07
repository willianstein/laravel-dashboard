@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Separação: Pedido de Saida Nº {{ $orderExit->id }}</h1>
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
                                        <!---<a class="nav-link" data-toggle="pill" href="#service-data" role="tab">Serviços</a>-->
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
                                                        placeholder="Unidade" value="{{ $orderExit->office->name }}"
                                                        disabled data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="" id=""
                                                        placeholder="Parceiro" value="{{ $orderExit->partner->name }}"
                                                        disabled data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="status" id="status" placeholder="Status"
                                                        disabled value="{{ $orderExit->status }}" data-toggle="tooltip"
                                                        class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="date" name="forecast" id="forecast" disabled
                                                        value="{{ $orderExit->forecast }}" placeholder="Data Prevista"
                                                        data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-5">
                                                    @can('concluir-separacao-pedido-saida')
                                                        <a href="{{ route('adm.separation.sendToConference', ['orderExit' => $orderExit->id]) }}"
                                                            class="btn btn-success float-right ajax-link ml-3">
                                                            <i class="fas fa-check"></i>
                                                            <span class="text-bold">Concluir Separação</span>
                                                        </a>
                                                    @endcan
                                                    @can('separar-proximo-pedido-saida')
                                                        <button data-toggle="modal" data-target="#modal-default"
                                                            class="btn btn-info float-right ml-3">
                                                            <i class="fas fa-search-location"></i>
                                                            <span class="text-bold">Separar Próximo</span>
                                                        </button>
                                                    @endcan
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- SERVICES DATA -->
                                    <div class="tab-pane fade" id="service-data" role="tabpanel">
                                        Service Data
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
                                            <th>Endereçamento</th>
                                            <th>ISBN</th>
                                            <th>Titulo</th>
                                            <th>Editora</th>
                                            <th>Disponíveis</th>
                                            <th>Quantidade</th>
                                            <th>Separado</th>
                                            <th>Status</th>
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
@endsection

@section('modal')
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Separar Próximo Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="form-separate"
                        action="{{ route('adm.separation.separateItem', ['orderExit' => $orderExit]) }}">
                        @csrf
                        <div class="form-row">

                            <div class="form-group col-12">
                                <label>ENDEREÇO: <span id="addressing_span"></span></label>
                                <input type="text" name="addressing" id="addressing" placeholder="Endereçamento"
                                    data-toggle="tooltip" class="form-control">
                                <input type="hidden" name="addressing_hdn" id="addressing_hdn">
                                <input type="hidden" name="stock_id" id="stock_id">
                            </div>

                            <div class="form-group col-12">
                                <label>ISBN: <span id="isbn_span"></span></label>
                                <input type="text" name="isbn" id="isbn" placeholder="ISBN Livro"
                                    data-toggle="tooltip" class="form-control">
                                <input type="hidden" name="isbn_hdn" id="isbn_hdn">
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
            loadDataTable("{{ route('adm.separation.getListOrderItems', ['orderExit' => $orderExit->id]) }}",
                'item-table');
            let noItems = false;

            $('#modal-default').on('show.bs.modal', function(e) {
                let url = '{{ route('adm.separation.separateNextItem', ['orderExit' => $orderExit]) }}';
                $.getJSON(url, function(response) {
                    console.log('Sucesso!');
                    /* Mensagem */
                    if (response.message) {
                        msgToast(response.message);
                    }
                    /* Ação */
                    if (response.action !== undefined) {
                        actions(response.action, response.data, 'form-separate');
                        /* Span */
                        $('#addressing_span').html(response.data.addressing_hdn);
                        $('#isbn_span').html(response.data.isbn_hdn);
                    } else {
                        noItems = true
                    }
                }).fail(function(response) {
                    console.log('Oops... ', response);
                });
            });

            $('#modal-default').on('shown.bs.modal', function(e) {
                if (noItems) {
                    console.log('Fechando Modal...');
                    $('#modal-default').modal('hide');
                }
            });

            $('#form-separate #isbn').keyup(function(event) {
                var charCode = (event.which) ? event.which : event.keyCode;
                if ((charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105)) {
                    if ($(this).val().length === 13) {
                        $('#form-separate').submit();
                    }
                }
            });

        });
    </script>
@endsection
