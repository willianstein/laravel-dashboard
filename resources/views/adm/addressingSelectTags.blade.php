@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Endereçamento (Etiquetas)</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Selecione as Etiquetas</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" id="myForm" action="{{route('adm.addressing.printTags')}}" class="ajax-off">
                                    <div class="form-row">
                                        <div class="col-12">
                                            <table id="table" class="table table-striped dataTable dtr-inline">
                                                <thead>
                                                <tr>
                                                    <th>Selecionar</th>
                                                    <th>Endereço</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-row justify-content-center">
                                        <div class="col-md-3">
                                            <button type="button" id="selecctall" class="btn btn-block btn-outline-info">Selecionar Todas</button>
                                        </div>
                                        <div class="col-md-3">
                                            @csrf
                                            <button type="submit" class="btn btn-block btn-outline-success">Imprimir Selecionadas</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
            let allcheck = false;
            loadDataTable("{{route('adm.addressing.getTags')}}", 'table');


            $('#selecctall').click(function(event) {  //on click
                if(!allcheck) { // check select status
                    allcheck = true;
                    console.log('Selecionando todos');
                    $('.checkbox').each(function() { //loop through each checkbox
                        this.checked = true;  //select all checkboxes with class "checkbox1"
                    });
                }else{
                    allcheck = false;
                    console.log('Deselecionando todos');
                    $('.checkbox').each(function() { //loop through each checkbox
                        this.checked = false; //deselect all checkboxes with class "checkbox1"
                    });
                }
            });

        });
    </script>
@endsection
