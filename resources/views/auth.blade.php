@extends('layouts.auth')

@section('content')

    <div class="content-login d-flex align-items-center">

        <div class="row w-100 justify-content-center">
            <div class="col-8 col-md-5 col-lg-4 col-xl-3">
                <div class="card elevation-5" style="border-radius: 0.5rem;">
                    <div class="card-body login-card-body" style="border-radius: 0.5rem;">
                        <div class="d-flex justify-content-center flex-wrap">
                            <img src="{{asset('img/logo-bb.jpg')}}" class="logo-bb" alt="BBems" title="BBems">
                            <p class="login-box-msg w-100">Por Favor, Identifique-se</p>
                        </div>
                        <form action="{{route('authController.login')}}" method="post">
                            <div class="input-group mb-4">
                                <input type="email" name="email" id="email" placeholder="E-mail" data-toggle="tooltip" class="form-control">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-4">
                                <input type="password" name="password" id="password" placeholder="Senha" data-toggle="tooltip" class="form-control">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 mb-4 m-md-0">
                                    <button type="button" class="btn btn-danger btn-block">Recuperar Senha</button>
                                </div>

                                <div class="col-12 col-md-6">
                                    @csrf
                                    <button class="btn btn-primary btn-block">Entrar</button>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection
