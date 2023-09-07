@extends('layouts.adm')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Meu Perfil</h1>
                </div>
                <!--                        <div class="col-sm-6">-->
                <!--                            <ol class="breadcrumb float-sm-right">-->
                <!--                                <li class="breadcrumb-item"><a href="#">Home</a></li>-->
                <!--                                <li class="breadcrumb-item"><a href="#">Layout</a></li>-->
                <!--                                <li class="breadcrumb-item active">Top Navigation</li>-->
                <!--                            </ol>-->
                <!--                        </div>-->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title m-0">Configurações Gerais</h5>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center p-3">
                                <form class="form-horizontal" id="formPhoto" method="post" action="{{route('adm.preference.updatePhoto',$user)}}">
                                    @csrf
                                    <label>
                                        <img src="{{(user()->photo?storage(user()->photo,'public'):asset('img/profile.png'))}}"
                                             id="preview_photo" alt="Imagem do Perfil" title="Imagem do Perfil"
                                             class="img-circle elevation-2 img-fluid" style="max-width: 135px;">
                                        <input type="file" name="photo" id="photo" style="display: none;">
                                    </label>
                                </form>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12 col-lg-8">
                                    <form class="form-horizontal" method="post" action="{{route('adm.preference.updatePassword',$user)}}">
                                        <p class="attachment-block rounded text-center my-4">
                                            Configurar Senha
                                        </p>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 text-right col-form-label">Senha Atual</label>
                                            <div class="col-sm-8">
                                                <input type="password" name="password_old" id="password_old" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 text-right col-form-label">Nova Senha</label>
                                            <div class="col-sm-8">
                                                <input type="password" name="password" id="password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 text-right col-form-label">Confirma Senha</label>
                                            <div class="col-sm-8">
                                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-5 justify-content-center">
                                            <div class="col-4">
                                                @csrf
                                                <button type="submit" class="btn btn-block btn-outline-success">Salvar</button>
                                            </div>
                                        </div>
                                    </form>
                                    <form class="form-horizontal" method="post" action="{{route('adm.preference.updatePreferences',$user)}}">
                                        <p class="attachment-block rounded text-center my-4">
                                            Configurar Estilo
                                        </p>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 text-right col-form-label">Tema</label>
                                            <div class="col-sm-8">
                                                <select class="custom-select" name="theme">
                                                    <option>Escuro</option>
                                                    <option>Misto</option>
                                                    <option>Claro</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 text-right col-form-label">Fonte</label>
                                            <div class="col-sm-8">
                                                <select class="custom-select" name="font">
                                                    <option>Pequena</option>
                                                    <option>Normal</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 text-right col-form-label">Menu</label>
                                            <div class="col-sm-8">
                                                <select class="custom-select" name="menu">
                                                    <option>Encolhido</option>
                                                    <option>Expandido</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row justify-content-center">
                                            <div class="col-4">
                                                @csrf
                                                <button type="submit" class="btn btn-block btn-outline-success">Salvar</button>
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

@endsection

@section('scripts')
<script>
    $("#photo").change(function(){
        const file = $(this)[0].files[0];
        const fileReader = new FileReader();
        fileReader.onloadend = function (){
            $('#preview_photo').attr('src',fileReader.result);
        }
        fileReader.readAsDataURL(file);
        $('#formPhoto').submit();
    });
</script>
@endsection
