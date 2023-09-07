@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Cadastro de Contas a Pagar</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!--FORM-->
                    @can('cadastrar-contas-a-pagar')
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h5 class="m-0">Cadastro</h5>
                                </div>
                                <div class="card-body">
                                    <form method="post" id="myForm">
                                        <div class="form-row">

                                            <div class="form-group col-md-3">
                                                <div class="input-group">
                                                    <input type="text" name="description" id="description"
                                                        data-placement="top" title="Descrição" placeholder="Descrição"
                                                        data-toggle="tooltip" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">R$</span>
                                                    </div>
                                                    <input type="text" name="value" id="value" data-placement="top"
                                                        data-placement="top" title="Valor" title="Saldo" placeholder="Valor"
                                                        pattern="^\d*(\.\d{0,2})?$" data-toggle="tooltip" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <select name="id_cost_center" id="filter_cost_center" class="custom-select"
                                                    data-placement="top" title="Centro de custo" placeholder="Centro de custo"
                                                    data-toggle="tooltip">
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <select name="id_bank" id="filter_bank" class="custom-select"
                                                    data-placement="top" title="Banco" placeholder="Banco"
                                                    data-toggle="tooltip">
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <div class="input-group">
                                                    <input type="date" name="date_expire" id="date_expire"
                                                        placeholder="Vencimento" data-placement="top" title="Vencimento"
                                                        data-toggle="tooltip" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <div class="input-group">
                                                    <input type="date" name="date_competence" id="date_competence"
                                                        placeholder="Competência" data-placement="top" title="Competência"
                                                        data-toggle="tooltip" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <select name="id_favored" id="partner_id" class="custom-select"
                                                    data-placement="top" title="Nome favorecido" placeholder="Nome favorecido"
                                                    data-toggle="tooltip">
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <select name="repetition" id="myOptions" class="custom-select"
                                                    data-placement="top" title="Tipo" placeholder="Tipo"
                                                    data-toggle="tooltip">
                                                    <option value="unico">Único</option>
                                                    <option value="semanal">Semanal</option>
                                                    <option value="quinzenal">Quinzenal</option>
                                                    <option value="mensal">Mensal</option>
                                                    <option value="parcelado">Parcelado</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2" type="hidden" id="parcelado">
                                                <div class="input-group">
                                                    <input type="number" name="parcelado" id="parcelado"
                                                        data-placement="top" title="Parcelas" placeholder="Parcelas"
                                                        data-toggle="tooltip" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2">
                                                @csrf
                                                <input type="hidden" name="id" id="id">
                                                <button class="btn btn-block btn-outline-info float-right">Salvar
                                                    Dados</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endcan
                    <!-- ITEMS -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Registros Cadastrados</h5>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table">
                                    <thead>
                                        <tr>
                                            <th>Descrição</th>
                                            <th>Valor</th>
                                            <th>Competência</th>
                                            <th>Vencimento</th>
                                            <th>Nome centro de custo</th>
                                            <th>Nome Banco</th>
                                            <th>Tipo</th>
                                            <th>Nome Favorecido</th>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#parcelado').hide();
            ajaxSelect2('filter_cost_center', '{{ route('financial.findCostCenter') }}', 'Centro de custo');
            ajaxSelect2('partner_id', '{{ route('adm.stock.getPartners') }}', 'Parceiro');
            ajaxSelect2('filter_bank', '{{ route('financial.findBank') }}', 'Banco');
            loadDataTable("{{ route('financial.getBills') }}", 'table');
            ajaxSelect2('partner_id', '{{ route('adm.stock.getPartners') }}', 'Nome Favorecido');
        });

        $('#myOptions').change(function() {
            var value = $("#myOptions option:selected").val();

            if (value == 'parcelado') {
                $('#parcelado').show();
            } else {
                $('#parcelado').hide();
            }
        });
    </script>
@endsection
