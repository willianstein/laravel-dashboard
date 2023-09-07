@extends ( Helper::isAdmin() ? 'layouts.adm' : 'layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pedidos de Saída</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#modal-default">
                            <i class="fas fa-plus"></i>
                            <span class="text-bold">NOVO PEDIDO</span>
                        </button>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Lista de Pedidos de Saída</h5>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table table-striped dataTable dtr-inline">
                                    <thead>
                                    <tr>
                                        <th>Criado em</th>
                                        <th>N⁰. Pedido</th>
                                        <th>Cod. Pedido</th>
                                        <th>Núm NF-e</th>
                                        <th>Unidade</th>
                                        <th>Parceiro</th>
                                        <th>Data Prevista</th>
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
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Novo Pedido</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="myForm" action="{{route('adm.orderExit.new')}}" class="ajax-off">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <select name="office_id" id="office_id" placeholder="Unidade Preferencial" class="form-control load-select" data-toggle="tooltip">
                                    <option value="">Selecione</option>
                                    @if(!empty($offices))
                                        @foreach($offices as $office)
                                            <option value="{{$office->id}}">{{$office->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @can('type-user')
                                <div class="form-group col-12">
                                    <select name="partner_id" id="partner_id" class="custom-select" placeholder="Parceiro" data-toggle="tooltip">
                                    </select>
                                </div>
                            @endcan
                            <div class="form-group col-12">
                                <input type="date" name="forecast" id="forecast" placeholder="Previsão de Saida" data-toggle="tooltip" class="form-control">
                            </div>

                            <div class="form-group col-12">
                                <textarea name="observations" id="observations" placeholder="Observações" data-toggle="tooltip" rows="4" class="form-control"></textarea>
                            </div>

                            <div class="form-group col-12">
                                @csrf
                                <input type="hidden" name="id" id="id">
                                <button class="btn btn-block btn-outline-info float-right">
                                    <small><i class="fas fa-plus"></i></small>
                                    <span class="text-bold">Criar Pedido</span>
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
    <link rel="stylesheet" href="{{asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('scripts')
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            ajaxSelect2('filter_partner_id', '{{route('adm.stock.getPartners')}}', 'Parceiro');
            ajaxSelect2('partner_id', '{{route('adm.stock.getPartners')}}', 'Parceiro');

            loadDataTable("{{route('adm.orderExit.getListOrders')}}", 'table');
        });
    </script>
@endsection
