<!doctype html>
<html lang="pt-br">
@include('layouts.snippets.head')
@php
    $dbCfg = json_decode(user()->theme_preferences ?? null);
    $cfg = new stdClass();
    $cfg->body = $dbCfg->body ?? 'text-sm sidebar-collapse';
    $cfg->nav = $dbCfg->nav ?? 'navbar-light';
    $cfg->aside = $dbCfg->aside ?? 'sidebar-light-dange';
@endphp

<body class="hold-transition sidebar-mini {{ $cfg->body }}">
    <div class="wrapper">

        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="{{ asset('img/logo-pequeno.jpg') }}" alt="BBEms" height="60"
                width="80">
        </div> --}}

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light {{ $cfg->nav }}">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('authController.logout') }}" role="button">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </li>
                {{--            <li class="nav-item"> --}}
                {{--                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"> --}}
                {{--                    <i class="fas fa-th-large"></i> --}}
                {{--                </a> --}}
                {{--            </li> --}}
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar {{ $cfg->aside }} elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('adm.dashboard.index') }}" class="brand-link">
                <img src="{{ asset('img/logo-pequeno.jpg') }}" alt="BBems" class="brand-image m-0 ml-2">
                <span class="brand-text mx-3 my-1"><b>BBEMS</b></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ user()->photo ? storage(user()->photo, 'public') : asset('img/profile.png') }}"
                            class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="{{ route('adm.preference.index') }}" class="d-block">{{ user()->name }}</a>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                        {{-- @can('dashboard') --}}
                        <li class="nav-item">
                            <a href="{{ route('adm.dashboard.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        {{-- @endcan --}}
                        @can('projeto')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-drafting-compass"></i>
                                    <p>Projetos</p>
                                </a>
                            </li>
                        @endcan
                        @can('cadastros')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-inbox"></i>
                                    <p>
                                        Cadastros
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>

                                <ul class="nav nav-treeview">
                                    @can('unidades')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.office.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-warehouse"></i>
                                                <p>Unidades</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('regras')
                                        <li class="nav-item">
                                            <a href="{{ route('roles.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-user-tie"></i>
                                                <p>Regras</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('produtos')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.product.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-book"></i>
                                                <p>Produto</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('servicos')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.service.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-concierge-bell"></i>
                                                <p>Serviços</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('enderecamentos')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.addressing.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-location-arrow"></i>
                                                <p>Endereçamento</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('estoque')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.stock.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-boxes"></i>
                                                <p>Estoque</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('pacote')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.package.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-box-open"></i>
                                                <p>Pacotes</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('usuarios')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.user.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-user"></i>
                                                <p>Usuário</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('categoria-ticket')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.ticketCategory.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-layer-group"></i>
                                                <p>Ticket (Categoria)</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('ticket')
                            <li class="nav-item">
                                <a href="{{ route('adm.ticket.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-ticket-alt"></i>
                                    <p>Tickets</p>
                                </a>
                            </li>
                        @endcan
                        @can('pedidos')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-handshake"></i>
                                    <p>
                                        Pedidos
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('adm.orderEntry.index') }}" class="nav-link">
                                            <i class="nav-icon fas fa-share"></i>
                                            <p>Entrada</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('adm.orderExit.index') }}" class="nav-link">
                                            <i class="nav-icon fas fa-reply"></i>
                                            <p>Saida</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('adm.orderReverse.index') }}" class="nav-link">
                                            <i class="nav-icon fas fa-undo"></i>
                                            <p>Reversa</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @can('separacao-pedidos')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-filter"></i>
                                    <p> Separação <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('adm.separation.index') }}" class="nav-link">
                                            <i class="nav-icon fas fa-search-location"></i>
                                            <p>Separar</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @can('conferencia')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-tasks"></i>
                                    <p>Conferência <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('conferencia-entrada')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.conferenceEntry.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-share"></i>
                                                <p>Entrada</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('conferencia-saida')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.conferenceExit.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-reply"></i>
                                                <p>Saida</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('expedicao')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-truck-loading"></i>
                                    <p>
                                        Expedição
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('expedicao-entrada')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.expeditionEntry.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-share"></i>
                                                <p>Entrada</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('expedicao-saida')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.expeditionExit.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-reply"></i>
                                                <p>Saida</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('expedicao-reversa')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.expeditionReverse.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-undo"></i>
                                                <p>Reversa</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('transporte')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-shipping-fast"></i>
                                    <p>
                                        Transporte
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('adm.transportRange.index') }}" class="nav-link">
                                            <i class="nav-icon fas fa-exchange-alt"></i>
                                            <p>Range de CEP</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('adm.partnerTransportRange.index') }}" class="nav-link">
                                            <i class="nav-icon fas fa-link"></i>
                                            <p>Vincular Range</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @can('financeiro')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-money-check-alt"></i>
                                    <p>
                                        Financeiro
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('centro-de-custo')
                                        <li class="nav-item">
                                            <a href="{{ route('finan.financial.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-share"></i>
                                                <p>Cadastrar Centro de Custo</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('banco')
                                        <li class="nav-item">
                                            <a href="{{ route('finan.financial.bank.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-donate"></i>
                                                <p>Cadastrar Banco</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('contas-a-pagar')
                                        <li class="nav-item">
                                            <a href="{{ route('financial.bills_to_pay.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                                <p>Contas a pagar</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('contas-a-receber')
                                        <li class="nav-item">
                                            <a href="{{ route('financial.billsToReceive.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-wallet"></i>
                                                <p>Contas a Receber</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('orcamentos')
                                        <li class="nav-item">
                                            <a href="{{ route('budget.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-file-contract"></i>
                                                <p>Orçamentos</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('ordem-de-compra')
                                        <li class="nav-item">
                                            <a href="{{ route('purchaseOrder.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-money-check"></i>
                                                <p>Ordem de Compra</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('caixinha')
                                        <li class="nav-item">
                                            <a href="{{ route('box.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-box"></i>
                                                <p>Caixinha</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('relatorio-financeiro')
                                        <li class="nav-item">
                                            <a href="{{ route('report.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-cloud-download-alt"></i>
                                                <p>Relatórios</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('relatorios')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-warehouse"></i>
                                    <p>Portaria</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-newspaper"></i>
                                    <p> Relatórios <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('relatorio-parceiro')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.reports.partner.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-search-location"></i>
                                                <p>Parceiro</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('relatorio-servico')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.reports.services.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-search-location"></i>
                                                <p>Serviços</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('relatorio-estoque')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.reports.stock.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-boxes"></i>
                                                <p>Estoque</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('relatorio-pedido-entrada')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.reports.orderEntry.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-search-location"></i>
                                                <p>Pedidos de Entrada</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('relatorio-pedido-saida')
                                        <li class="nav-item">
                                            <a href="{{ route('adm.reports.orderExit.index') }}" class="nav-link">
                                                <i class="nav-icon fas fa-search-location"></i>
                                                <p>Pedidos de Saída</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>


        @yield('content')
    </div>

    @yield('modal')

    @include('layouts.snippets.scripts')
</body>

</html>
