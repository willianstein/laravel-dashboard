@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Separação de Pedidos</h1>
                    </div><!-- /.col -->
                    @can('separar-separacao-pedidos')
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success float-right" data-toggle="modal"
                                data-target="#modal-separate">
                                <i class="fas fa-layer-group"></i>
                                <span class="text-bold">Separar Pela Ficha</span>
                            </button>
                        </div>
                    @endcan
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- ITEMS -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Lista de Pedidos a Serem Conferidos</h5>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table table-striped dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>Criado em</th>
                                            <th>N⁰ Pedido</th>
                                            <th>Cod. Pedido</th>
                                            <th>Unidade</th>
                                            <th>Parceiro</th>
                                            <th>Status</th>
                                            <th>Data Prevista</th>
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
    <div class="modal fade" id="modal-separate">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Novo Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="myForm" action="{{ route('adm.separation.inBatch') }}">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <input type="number" name="order_id" id="order_id" placeholder="Número da Ficha"
                                    data-toggle="tooltip" class="form-control">
                            </div>

                            <div class="form-group col-12">
                                @csrf
                                <button class="btn btn-block btn-outline-info float-right">
                                    <small><i class="fas fa-plus"></i></small>
                                    <span class="text-bold">Concluir Separação</span>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            loadDataTable("{{ route('adm.separation.getListOrders') }}", 'table');
            actionInterval(30, {
                'reloadDataTable': 'table'
            });
        });
    </script>
@endsection
