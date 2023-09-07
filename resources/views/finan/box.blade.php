@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Caixinha</h1>
                    </div><!-- /.col -->
                    @can('cadastrar-caixinha')
                        <div class="col-sm-6">
                            <div class="text-right">
                                <button type="button" class="btn btn-success float-right" data-toggle="modal"
                                    data-target="#modal-default">
                                    <i class="fas fa-plus"></i>
                                    <span class="text-bold">NOVA CAIXINHA</span>
                                    {{-- <button type="button" class="btn btn-success float-right" data-toggle="modal"
                            data-target="#modal-default">
                            <i class="fas fa-plus"></i>
                            <span class="text-bold">NOVA CAIXINHA</span> --}}
                            </div>
                            {{-- <button type="button" class="btn btn-success float-right" data-toggle="modal"
                            data-target="#modal-default">
                            <i class="fas fa-plus"></i>
                            <span class="text-bold">NOVA CAIXINHA</span>
                        </button> --}}
                        </div>
                    @endcan
                    {{-- <div class="col-sm-6">
                        <button type="button" class="btn btn-success float-right" data-toggle="modal"
                            data-target="#modal-default">
                            <i class="fas fa-plus"></i>
                            <span class="text-bold">NOVA CAIXINHA</span>
                        </button>
                    </div> --}}
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
                                <div class="card-title">
                                    <h5 class="m-0">Itens</h5>
                                </div>
                                <div class="card-tools"></div>
                            </div>

                            <div class="card-body">
                                <table id="table" class="table table-striped dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Valor Caixinha</th>
                                            <th>Setor</th>
                                            <th>Banco</th>
                                            <th>Responsável</th>
                                            <th>Status</th>
                                            <th>Fechar</th>
                                            <th>Movimentações</th>

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
                    <form method="POST" id="myForm" action="{{ route('toBox.save') }}">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <select name="id_bank" id="filter_bank" class="custom-select" placeholder="Banco"
                                    data-toggle="tooltip">
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <select name="id_sector" id="filter_sector" class="custom-select" placeholder="Setor"
                                    data-toggle="tooltip">
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" name="value" id="value" data-placement="top"
                                        data-placement="top" title="Valor" title="Saldo" placeholder="Valor"
                                        pattern="^\d*(\.\d{0,2})?$" data-toggle="tooltip" class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <input type="text" name="responsible" id="responsible"
                                        placeholder="Nome do Responsável" data-toggle="tooltip" class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <div class="input-group">
                                    <input type="text" name="goal" id="goal" placeholder="Finalidade"
                                        data-toggle="tooltip" class="form-control">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <select name="id_cost_center" id="filter_cost_center" class="custom-select"
                                    placeholder="Centro de custo" data-toggle="tooltip">
                                </select>
                            </div>

                            <div class="form-group col-12">
                                @csrf
                                <input type="hidden" name="id" id="id">
                                <button class="btn btn-block btn-outline-info float-right">Cria Caixinha</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-reset">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <meta name="csrf-token" content="{{ csrf_token() }}" />
                    <h4 class="modal-title">Pedido Repor Caixinha</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="myForm" name="myForm" class="ajax-off">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" name="value" id="value" data-placement="top"
                                        data-placement="top" title="Valor" title="Saldo" placeholder="Valor"
                                        pattern="^\d*(\.\d{0,2})?$" data-toggle="tooltip" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="input-group">
                                    <input type="text" name="observation" id="observation" placeholder="Observação"
                                        data-toggle="tooltip" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group col-12">
                                @csrf
                                <input type="hidden" name="id" id="id">
                                <button class="btn btn-block btn-outline-info float-right">Repor
                                    Caixinha</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-remove">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <meta name="csrf-token" content="{{ csrf_token() }}" />
                    <h4 class="modal-title">Pedido Retirada Caixinha</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="myFormRemove" name="myFormRemove" class="ajax-off">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="text" name="value" id="value" data-placement="top"
                                        data-placement="top" title="Valor" title="Saldo" placeholder="Valor"
                                        pattern="^\d*(\,\d{0,2})?$" data-toggle="tooltip" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="input-group">
                                    <input type="text" name="observation" id="observation" placeholder="Observação"
                                        data-toggle="tooltip" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group col-12">
                                @csrf
                                <input type="hidden" name="id" id="id">
                                <button class="btn btn-block btn-outline-info float-right">Retirar da Caixinha ?</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head')
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            loadDataTable("{{ route('box.getToBox') }}", 'table');
            ajaxSelect2('filter_bank', '{{ route('financial.findBank') }}', 'Banco');
            ajaxSelect2('filter_sector', '{{ route('financial.findSector') }}', 'Setor');
            ajaxSelect2('filter_cost_center', '{{ route('financial.findCostCenter') }}', 'Centro de custo');
        });
    </script>
    <script>
        function OpenModalFor(id) {
            $('#modal-reset').modal('show');

            $('form[name="myForm"]').submit(function(event) {
                event.preventDefault();
                var value = $(this).find('input#value').val()
                var observation = $(this).find('input#observation').val()

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('box.resetBox') }}",
                    type: "post",
                    data: {
                        id: id,
                        value: value,
                        observation: observation
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#modal-reset').modal('hide');
                        location.reload();
                    }

                })
            })

        }

        function OpenModalForRemove(id) {
            $('#modal-remove').modal('show');

            $('form[name="myFormRemove"]').submit(function(event) {
                event.preventDefault();
                var value = $(this).find('input#value').val()
                var observation = $(this).find('input#observation').val()

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('box.removetBox') }}",
                    type: "post",
                    data: {
                        id: id,
                        value: value,
                        observation: observation
                    },
                    dataType: 'json',
                    success: function(html) {
                        $('#modal-reset').modal('hide');
                        location.reload();
                    },
                    error: function(data, errorThrown) {
                        var response = JSON.parse(data.responseText);
                        $('#modal-remove').modal('hide');
                        alert(response.message);
                    }
                })
            })

        }
    </script>
    <script>
        function hideModal() {
            location.reload();
        }
    </script>
@endsection
