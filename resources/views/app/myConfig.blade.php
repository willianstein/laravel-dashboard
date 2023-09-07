@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Configurações</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-2">
                        @include('app.snippets.configMenu')
                    </div>
                    <div class="col-12 col-md-10 p-1">
                        <p class="text-bold">Acesso ao BBEms</p>
                        <p>Aqui podemos configurar as integrações do BBEms com outros sistemas.</p>
                        <p>Caso queira que outro sistema venha buscar informações aqui no BBEms, então precisaremos
                            criar um token de acesso. Esse token será o seu passaporte para acessar as
                            informações dentro do BBEms. Neste caso basta seguir os seguintes passos:</p>
                        <ul>
                            <li>Clique em "Acesso ao BBEms" (no menu ao lado)</li>
                            <li>depois "Gerar Token" selecione o parceiro, em seguida de um
                                nome para o seu token. O nome irá ajudar na identificação do token ex: "Token Aaplicativo"
                                "Token Sistema A", etc...</li>
                        </ul>
                        <p>Caso queria ver os tokens que estão criados basta:</p>
                        <ul>
                            <li>Clique em "Acesso ao BBEms" (no menu ao lado)</li>
                            <li>Selecione o Parceiro na lista suspensa. Então o sistema irá listar todos os tokens disponiveis.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
