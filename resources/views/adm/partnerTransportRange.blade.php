@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Range do Parceiro</h1>
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
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Manutenção do Cadastro</h5>
                            </div>
                            <div class="card-body">
                                <form method="post" id="myForm" class="ajax-off">
                                    <div class="form-row">

                                        <div class="form-group col-md-10">
                                            <?=form_select('partner_id',($dropPartners??null),(old('partner_id')??null),['class'=>'custom-select select2','data-toggle'=>'tooltip'],'Escolha o Parceiro');?>
                                        </div>

                                        <div class="form-group col-md-2">
                                            @csrf
                                            <input type="hidden" name="id" id="id">
                                            <button class="btn btn-block btn-outline-info float-right">Selecionar o Parceiro</button>
                                        </div>

                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <table id="table" class="table table-striped dataTable dtr-inline">
                                            <thead>
                                            <tr>
                                                <th>Range</th>
                                                <th>Cep Inicial</th>
                                                <th>Cep Final</th>
                                                <th>Selecionar</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(!empty($transportRanges))
                                            @foreach($transportRanges as $transportRange)
                                                <tr>
                                                    <td>{{$transportRange->name}}</td>
                                                    <td>{{$transportRange->range_from}}</td>
                                                    <td>{{$transportRange->range_up_to}}</td>
                                                    <td>
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox"
                                                                   name="enabled-{{$transportRange->id}}"
                                                                   id="enabled-{{$transportRange->id}}" {{($transportRange->enabled ? "checked" : null)}}
                                                                   data-url="{{route('adm.partnerTransportRange.save',[$transportRange,'partner'=>old('partner_id')])}}"
                                                                   class="custom-control-input ajax-check"
                                                            >
                                                            <label class="custom-control-label" for="enabled-{{$transportRange->id}}">Habilitado?</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @endif
                                            </tbody>
                                        </table>
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
        $(document).ready(function(){
            //loadDataTable("{{route('adm.transportRange.getTransportRanges')}}", 'table');

            ajaxSelect2('partner_id', '{{route('adm.stock.getPartners')}}', 'Parceiro');
        });
    </script>
@endsection

