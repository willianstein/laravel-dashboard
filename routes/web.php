<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Adm\Ticket;
use App\Http\Controllers\Adm\ExpeditionReverse;
use App\Http\Controllers\Adm\OrderReverse;
use App\Http\Controllers\Adm\TransportTag;
use App\Http\Controllers\Adm\Preference;
use App\Http\Controllers\Adm\ExpeditionExit;
use App\Http\Controllers\Adm\ConferenceEntry;
use App\Http\Controllers\Adm\ExpeditionEntry;
use App\Http\Controllers\Adm\OrderExit;
use App\Http\Controllers\Adm\OrderEntry;
use App\Http\Controllers\Adm\OrderItem;
use App\Http\Controllers\Adm\Dashboard;
use App\Http\Controllers\Adm\Partner;
use App\Http\Controllers\Adm\Product;
use App\Http\Controllers\Adm\ProductImport;
use App\Http\Controllers\Adm\Service;
use App\Http\Controllers\Adm\Addressing;
use App\Http\Controllers\Adm\Office;
use App\Http\Controllers\Adm\Recipient;
use App\Http\Controllers\Adm\Stock;
use App\Http\Controllers\Adm\StockImport;
use App\Http\Controllers\Adm\Separation;
use App\Http\Controllers\Adm\Package;
use App\Http\Controllers\Adm\UserController;
use App\Http\Controllers\Adm\ConferenceExit;
use App\Http\Controllers\Adm\TransportRange;
use App\Http\Controllers\Adm\PartnerTransportRange;
use App\Http\Controllers\Adm\FinancialController;

use App\Http\Controllers\Adm\Reports\Partner as ReportPartner;
use App\Http\Controllers\Adm\Reports\Service as ReportService;
use App\Http\Controllers\Adm\Reports\Stock as ReportStock;
use App\Http\Controllers\Adm\Reports\OrderEntry as ReportOrderEntry;
use App\Http\Controllers\Adm\Reports\OrderExit as ReportOrderExit;
use App\Http\Controllers\Adm\TicketCategory;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
    *  LOGIN ROUTES
    */

Route::get('/', [AuthController::class, 'index'])->name('authController.index');
Route::post('/login', [AuthController::class, 'login'])->name('authController.login');
Route::get('/sair', [AuthController::class, 'logout'])->name('authController.logout');


Route::middleware(['auth', 'can:type-user'])->group(function () {
    // Route::middleware(['auth'])->group(function () {

    /*
    *  ADM ROUTES
    */
    Route::prefix('dashboards')->group(function () {
        Route::get('/', [Dashboard::class, 'index'])->name('adm.dashboard.index');
        Route::get('/orderTracking', [Dashboard::class, 'getBanner'])->name('adm.dashboard.getBanner');
        Route::get('/orderTracking/data', [Dashboard::class, 'getBannerData'])->name('adm.dashboard.getBannerData');
    });

    /* Preferencias */
    Route::prefix('preferencias')->group(function () {
        Route::get('/', [Preference::class, 'index'])->name('adm.preference.index');
        Route::post('/{user}/atualizar-foto', [Preference::class, 'updatePhoto'])->name('adm.preference.updatePhoto');
        Route::post('/{user}/mudar-senha', [Preference::class, 'updatePassword'])->name('adm.preference.updatePassword');
        Route::post('/{user}/salvar-preferencias', [Preference::class, 'updatePreferences'])->name('adm.preference.updatePreferences');
    });

    /* Unidades */
    Route::prefix('unidades')->group(function () {
        Route::get('/', [Office::class, 'index'])->name('adm.office.index');
        Route::post('/salvar', [Office::class, 'save'])->name('adm.office.save');
        Route::get('/listar', [Office::class, 'getOffices'])->name('adm.office.getOffices');
        Route::get('/listar/{office}', [Office::class, 'getOffice'])->name('adm.office.getOffice');
        Route::get('/ativa-desativa/{office}', [Office::class, 'onOff'])->name('adm.office.onOff');
    });

    /* Parceiros */
    Route::prefix('parceiros')->group(function () {
        Route::post('/buscar', [Partner::class, 'search'])->name('adm.partner.search');
        Route::post('/{partner?}', [Partner::class, 'save'])->name('adm.partner.save');
        Route::get('/{partner?}', [Partner::class, 'index'])->name('adm.partner.index');

        Route::post('/{partner?}/enderecos', [Partner::class, 'saveAddress'])->name('adm.partner.saveAddress');
        Route::get('/{partner?}/enderecos', [Partner::class, 'getAddresses'])->name('adm.partner.getAddresses');
        Route::get('/{partner}/enderecos/ativa-desativa/', [Partner::class, 'onOffAddress'])->name('adm.partner.onOffAddress');
        Route::get('/{partner}/enderecos/{address?}', [Partner::class, 'getAddress'])->name('adm.partner.getAddress');

        Route::post('/{partner?}/contatos', [Partner::class, 'saveContact'])->name('adm.partner.saveContact');
        Route::get('/{partner?}/contatos', [Partner::class, 'getContacts'])->name('adm.partner.getContacts');
        Route::get('/{partner?}/contatos/ativa-desativa', [Partner::class, 'onOffContact'])->name('adm.partner.onOffContact');
        Route::get('/{partner?}/contatos/{contact}', [Partner::class, 'getContact'])->name('adm.partner.getContact');

        Route::post('/{partner?}/servicos', [Partner::class, 'saveService'])->name('adm.partner.saveService');
        Route::get('/{partner?}/servicos', [Partner::class, 'getServices'])->name('adm.partner.getServices');
        Route::get('/{partner?}/servicos/{service}', [Partner::class, 'getService'])->name('adm.partner.getService');
        Route::get('/{partner?}/servicos/{service}/ativa-desativa', [Partner::class, 'onOffService'])->name('adm.partner.onOffService');
    });

    /* Produtos */
    Route::prefix('produtos')->group(function () {
        Route::get('/', [Product::class, 'index'])->name('adm.product.index');
        Route::post('/', [Product::class, 'save'])->name('adm.product.save');
        Route::get('/listar', [Product::class, 'getProducts'])->name('adm.product.getProducts');
        Route::get('/editar/{product}', [Product::class, 'getProduct'])->name('adm.product.getProduct');
        Route::get('/buscar/{term}', [Product::class, 'findProduct'])->name('adm.product.findProduct');
        Route::get('/ativa-desativa/{product}', [Product::class, 'onOff'])->name('adm.product.onOff');

        /* Ações em Lotes */
        Route::prefix('importar')->group(function () {
            /* CSV */
            Route::prefix('csv')->group(function () {
                Route::get('/', [ProductImport::class, 'sendCsv'])->name('adm.productImport.sendCsv');
                Route::post('/', [ProductImport::class, 'loadCsv'])->name('adm.productImport.loadCsv');
                Route::post('/processar', [ProductImport::class, 'processCsv'])->name('adm.productImport.processCsv');
            });
        });
    });

    /* Serviços */
    Route::prefix('servicos')->group(function () {
        Route::post('/', [Service::class, 'save'])->name('adm.service.save');
        Route::get('/', [Service::class, 'index'])->name('adm.service.index');
        Route::get('/listar', [Service::class, 'getServices'])->name('adm.service.getServices');
        Route::get('/listar/{service}', [Service::class, 'getService'])->name('adm.service.getService');
        Route::get('/ativa-desativa/{service}', [Service::class, 'onOff'])->name('adm.service.onOff');
    });

    /* Endereçamentos */
    Route::prefix('enderecamento')->group(function () {
        Route::post('/', [Addressing::class, 'save'])->name('adm.addressing.save');
        Route::get('/', [Addressing::class, 'index'])->name('adm.addressing.index');
        Route::get('/listar', [Addressing::class, 'getAddressings'])->name('adm.addressing.getAddressings');
        Route::get('/editar/{addressing}', [Addressing::class, 'getAddressing'])->name('adm.addressing.getAddressing');
        Route::get('/ativa-desativa/{addressing}', [Addressing::class, 'onOff'])->name('adm.addressing.onOff');

        Route::get('imprimir-etiqueta', [Addressing::class, 'selectTags'])->name('adm.addressing.selectTags');
        Route::get('buscar-etiqueta', [Addressing::class, 'getTags'])->name('adm.addressing.getTags');
        Route::post('imprimir-etiqueta', [Addressing::class, 'printTags'])->name('adm.addressing.printTags');
    });

    /* Estoque */
    Route::prefix('estoque')->group(function () {
        Route::post('/', [Stock::class, 'save'])->name('adm.stock.save');
        Route::get('/', [Stock::class, 'index'])->name('adm.stock.index');
        Route::get('/listar', [Stock::class, 'getStocks'])->name('adm.stock.getStocks');
        Route::get('/editar/{stock}', [Stock::class, 'getStock'])->name('adm.stock.getStock');
        Route::get('/ativa-desativa/{stock}', [Stock::class, 'onOff'])->name('adm.stock.onOff');

        Route::get('/busca-parceiro/{term?}', [Stock::class, 'getPartners'])->name('adm.stock.getPartners');
        Route::get('/busca-produto/{term?}', [Stock::class, 'getProducts'])->name('adm.stock.getProducts');
        Route::get('/busca-enderecamento/{term?}', [Stock::class, 'getAddressing'])->name('adm.stock.getAddressing');

        Route::get('/por-parceiro-produto/{partner?}/{product?}/{type?}', [Stock::class, 'getDropByPartnerAndProduct'])->name('adm.stock.getDropByPartnerAndProduct');

        /* Ações em Lotes */
        Route::prefix('importar')->group(function () {
            /* CSV */
            Route::prefix('csv')->group(function () {
                Route::get('/', [StockImport::class, 'sendCsv'])->name('adm.stockImport.sendCsv');
                Route::post('/', [StockImport::class, 'loadCsv'])->name('adm.stockImport.loadCsv');
                Route::post('/processar', [StockImport::class, 'processCsv'])->name('adm.stockImport.processCsv');
            });
        });
    });

    /* Pacotes */
    Route::prefix('pacote')->group(function () {
        Route::post('/', [Package::class, 'save'])->name('adm.package.save');
        Route::get('/', [Package::class, 'index'])->name('adm.package.index');
        Route::get('/listar', [Package::class, 'getPackages'])->name('adm.package.getPackages');
        Route::get('/editar/{package}', [Package::class, 'getPackage'])->name('adm.package.getPackage');
        Route::get('/ativa-desativa/{package}', [Package::class, 'onOff'])->name('adm.package.onOff');
    });

    /* Usuarios */
    Route::prefix('usuario')->group(function () {
        Route::post('/', [UserController::class, 'save'])->name('adm.user.save');
        Route::get('/create/{partner?}', [UserController::class, 'create'])->name('adm.user.create');
        Route::get('/', [UserController::class, 'index'])->name('adm.user.index');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('users.update');
        Route::get('/listar', [UserController::class, 'getUsers'])->name('adm.user.getUsers');
        Route::get('/editar/{user}', [UserController::class, 'getUser'])->name('adm.editUser');
        Route::get('/ativa-desativa/{user}', [UserController::class, 'onOff'])->name('adm.user.onOff');
    });

    /* Ticket */
    Route::prefix('ticket')->group(function () {

        Route::get('/', [Ticket::class, 'index'])->name('adm.ticket.index');
        Route::post('/', [Ticket::class, 'save'])->name('adm.ticket.save');
        Route::get('/listar', [Ticket::class, 'getTickets'])->name('adm.ticket.getTickets');
        Route::get('/gerenciar/{ticket}', [Ticket::class, 'manager'])->name('adm.ticket.manager');
        Route::post('/gerenciar/{ticket}/nova-mensagem', [Ticket::class, 'sendMessage'])->name('adm.ticket.sendMessage');
        Route::post('/gerenciar/{ticket}/transferir', [Ticket::class, 'transfer'])->name('adm.ticket.transfer');
        Route::post('/gerenciar/{ticket}/comentar', [Ticket::class, 'comment'])->name('adm.ticket.comment');
        Route::get('/gerenciar/{ticket}/finalizar', [Ticket::class, 'finish'])->name('adm.ticket.finish');

        /* Categoria */
        Route::prefix('categoria')->group(function () {
            Route::post('/', [TicketCategory::class, 'save'])->name('adm.ticketCategory.save');
            Route::get('/', [TicketCategory::class, 'index'])->name('adm.ticketCategory.index');
            Route::get('/listar', [TicketCategory::class, 'getTicketCategories'])->name('adm.ticketCategory.getTicketCategories');
            Route::get('/editar/{ticketCategory}', [TicketCategory::class, 'getTicketCategory'])->name('adm.ticketCategory.getTicketCategory');
            Route::get('/ativa-desativa/{ticketCategory}', [TicketCategory::class, 'onOff'])->name('adm.ticketCategory.onOff');
        });
    });

    /* Pedidos */
    Route::prefix('pedidos')->group(function () {

        /* Entrada */
        Route::prefix('entrada')->group(function () {
            Route::get('/', [OrderEntry::class, 'index'])->name('adm.orderEntry.index');
            Route::post('/novo', [OrderEntry::class, 'new'])->name('adm.orderEntry.new');
            Route::get('/listar', [OrderEntry::class, 'getListOrders'])->name('adm.oderEntry.getListOrders');
            Route::get('/{orderEntry}', [OrderEntry::class, 'manager'])->name('adm.orderEntry.manager');
            Route::get('/{orderEntry}/listar-items', [OrderEntry::class, 'getListOrderItems'])->name('adm.orderEntry.getListOrderItems');

            Route::post('/{orderEntry}/transporte', [OrderEntry::class, 'transport'])->name('adm.orderEntry.transport');
            Route::post('/{orderEntry}/atualizar-previsao', [OrderEntry::class, 'updateForecast'])->name('adm.orderEntry.updateForecast');
            Route::get('/{orderEntry}/cancelar', [OrderEntry::class, 'cancel'])->name('adm.orderEntry.cancel');
            Route::get('/{orderEntry}/aguardar-recebimento', [OrderEntry::class, 'receive'])->name('adm.orderEntry.receive');
            Route::post('/{orderEntry}/adicionar-item', [OrderEntry::class, 'addItem'])->name('adm.orderEntry.addItem');
            Route::get('/{orderEntry}/remover-item/{orderItem}', [OrderEntry::class, 'removeItem'])->name('adm.orderEntry.removeItem');
        });

        /* Reversa */
        Route::prefix('reversa')->group(function () {
            Route::get('/', [OrderReverse::class, 'index'])->name('adm.orderReverse.index');
            Route::get('/listar', [OrderReverse::class, 'getListOrders'])->name('adm.orderReverse.getListOrders');
        });

        /* Saida */
        Route::prefix('saida')->group(function () {
            Route::get('/', [OrderExit::class, 'index'])->name('adm.orderExit.index');
            Route::post('/novo', [OrderExit::class, 'new'])->name('adm.orderExit.new');
            Route::get('/listar', [OrderExit::class, 'getListOrders'])->name('adm.orderExit.getListOrders');
            Route::get('/{orderExit}', [OrderExit::class, 'manager'])->name('adm.orderExit.manager');
            Route::get('/{orderExit}/listar-itens', [OrderExit::class, 'getListOrderItems'])->name('adm.orderExit.getListOrderItems');

            Route::get('/{orderExit}/atualizar-previsao', [OrderExit::class, 'updateForecast'])->name('adm.orderExit.updateForecast');
            Route::get('/{orderExit}/separar', [OrderExit::class, 'breakApart'])->name('adm.orderExit.breakApart');
            Route::get('/{orderExit}/cancelar', [OrderExit::class, 'cancel'])->name('adm.orderExit.cancel');
            Route::post('/{orderExit}/destinatario', [OrderExit::class, 'recipient'])->name('adm.orderExit.recipient');
            Route::post('/{orderExit}/transporte', [OrderExit::class, 'transport'])->name('adm.orderExit.transport');
            Route::post('/{orderExit}/adicionar-item', [OrderExit::class, 'addItem'])->name('adm.orderExit.addItem');
            Route::get('/{orderExit}/remover-item/{orderItem}', [OrderExit::class, 'removeItem'])->name('adm.orderExit.removeItem');

            Route::post('/{orderExit}/adicionar-nota', [OrderExit::class, 'addNfe'])->name('adm.orderExit.addNfe');
        });
    });

    /* Expedição */
    Route::prefix('expedicao')->group(function () {
        /* Entrada */
        Route::prefix('entrada')->group(function () {
            Route::get('/', [ExpeditionEntry::class, 'index'])->name('adm.expeditionEntry.index');
            Route::get('/listar', [ExpeditionEntry::class, 'getListOrders'])->name('adm.expeditionEntry.getListOrders');
            Route::get('/{orderEntry}', [ExpeditionEntry::class, 'manager'])->name('adm.expeditionEntry.manager');
            Route::get('/{orderEntry}/listar-itens', [ExpeditionEntry::class, 'getListOrderItems'])->name('adm.expeditionEntry.getListOrderItems');
            Route::get('/{orderEntry}/receber', [ExpeditionEntry::class, 'received'])->name('adm.expeditionEntry.received');
            Route::get('/{orderEntry}/enviar-conferencia', [ExpeditionEntry::class, 'sendToCheck'])->name('adm.expeditionEntry.sendToCheck');
            Route::post('/{orderItem?}/recebe-item', [ExpeditionEntry::class, 'receiveItem'])->name('adm.expeditionEntry.receiveItem');
            Route::post('/{orderItem?}/recusa-item', [ExpeditionEntry::class, 'refuseItem'])->name('adm.expeditionEntry.refuseItem');
        });

        /* Reversa */
        Route::prefix('reversa')->group(function () {
            Route::get('/', [ExpeditionReverse::class, 'index'])->name('adm.expeditionReverse.index');
            Route::get('/listar', [ExpeditionReverse::class, 'getListOrders'])->name('adm.expeditionReverse.getListOrders');
        });

        /* Saida */
        Route::prefix('saida')->group(function () {
            Route::get('/', [ExpeditionExit::class, 'index'])->name('adm.expeditionExit.index');
            Route::get('/listar', [ExpeditionExit::class, 'getListOrders'])->name('adm.expeditionExit.getListOrders');
            Route::get('/{orderExit}', [ExpeditionExit::class, 'manager'])->name('adm.expeditionExit.manager');
            Route::get('/{orderExit}/concluir', [ExpeditionExit::class, 'complete'])->name('adm.expeditionExit.complete');
            Route::get('/{orderExit}/listar-itens', [ExpeditionExit::class, 'getListOrderItems'])->name('adm.expeditionExit.getListOrderItems');
            Route::post('/{orderItem?}/concluir-item', [ExpeditionExit::class, 'completeItem'])->name('adm.expeditionExit.completeItem');
            Route::post('/{orderExit?}/atualizar-transporte', [ExpeditionExit::class, 'updateTransport'])->name('adm.expeditionExit.updateTransport');

            Route::get('/{orderExit}/gerar-etiqueta-transporte', [TransportTag::class, 'generate'])->name('adm.transportTag.generate');
            Route::get('/{orderExit}/imprimir-etiqueta-transporte', [TransportTag::class, 'print'])->name('adm.transportTag.print');
            Route::get('/{orderExit}/imprimir-danfe-etiqueta', [ExpeditionExit::class, 'printSimpleDanfe'])->name('adm.expeditionExit.printSimpleDanfe');
            Route::get('/{orderExit}/imprimir-danfe', [ExpeditionExit::class, 'printDanfe'])->name('adm.expeditionExit.printDanfe');
            Route::get('/{orderExit}/imprimir-declaracao', [ExpeditionExit::class, 'printDeclaration'])->name('adm.expeditionExit.printDeclaration');


            Route::post('/concluir-pedido/{orderExit}', [ExpeditionExit::class, 'boardingAll'])->name('adm.expeditionExit.boardingAll');

        });
    });

    /* Separação */
    Route::prefix('separacao')->group(function () {
        Route::get('/', [Separation::class, 'index'])->name('adm.separation.index');
        Route::get('/listar', [Separation::class, 'getListOrders'])->name('adm.separation.getListOrders');
        Route::post('/separar-lote', [Separation::class, 'inBatch'])->name('adm.separation.inBatch');
        Route::get('/{orderExit}', [Separation::class, 'manager'])->name('adm.separation.manager');
        Route::get('/{orderExit}/imprimir', [Separation::class, 'print'])->name('adm.separation.print');
        Route::get('/{orderExit}/listar-itens', [Separation::class, 'getListOrderItems'])->name('adm.separation.getListOrderItems');
        Route::get('/{orderExit}/separar-proximo', [Separation::class, 'separateNextItem'])->name('adm.separation.separateNextItem');
        Route::post('/{orderExit}/separar-proximo', [Separation::class, 'separateItem'])->name('adm.separation.separateItem');
        Route::get('/{orderExit}/concluir', [Separation::class, 'sendToConference'])->name('adm.separation.sendToConference');
    });

    /* Conferencia */
    Route::prefix('conferencia')->group(function () {
        /* Entrada */
        Route::prefix('entrada')->group(function () {
            Route::get('/', [ConferenceEntry::class, 'index'])->name('adm.conferenceEntry.index');
            Route::get('/listar', [ConferenceEntry::class, 'getListOrders'])->name('adm.conferenceEntry.getListOrders');
            Route::get('/{orderEntry}', [ConferenceEntry::class, 'manager'])->name('adm.conferenceEntry.manager');
            Route::get('/{orderEntry}/listar-itens', [ConferenceEntry::class, 'getListOrderItems'])->name('adm.conferenceEntry.getListOrderItems');
            Route::get('/{orderEntry}/concluir', [ConferenceEntry::class, 'checked'])->name('adm.conferenceEntry.checked');
            Route::post('/{orderEntry}/conferir/{orderItem?}', [ConferenceEntry::class, 'checkItem'])->name('adm.conferenceEntry.checkItem');
            Route::post('/{orderEntry}/descartar/{orderItem?}', [ConferenceEntry::class, 'discardItem'])->name('adm.conferenceEntry.discardItem');

            Route::post('/{orderEntry}/adicionar-servico', [ConferenceEntry::class, 'addService'])->name('adm.conferenceEntry.addService');
            Route::get('/{orderEntry}/listar-servico', [ConferenceEntry::class, 'getServices'])->name('adm.conferenceEntry.getServices');
        });

        /* Saida */
        Route::prefix('saida')->group(function () {
            Route::get('/', [ConferenceExit::class, 'index'])->name('adm.conferenceExit.index');
            Route::get('/listar', [ConferenceExit::class, 'getListOrders'])->name('adm.conferenceExit.getListOrders');
            Route::get('/{orderExit}', [ConferenceExit::class, 'manager'])->name('adm.conferenceExit.manager');
            Route::get('/{orderExit}/listar-itens', [ConferenceExit::class, 'getListOrderItems'])->name('adm.conferenceExit.getListOrderItems');
            Route::post('/{orderExit}/conferir', [ConferenceExit::class, 'checkItem'])->name('adm.conferenceExit.checkItem');
            Route::get('/{orderExit}/concluir', [ConferenceExit::class, 'checked'])->name('adm.conferenceExit.checked');

            Route::post('/{orderExit}/adicionar-pacote', [ConferenceExit::class, 'addPackage'])->name('adm.conferenceExit.addPackage');
            Route::get('/{orderExit}/listar-pacote', [ConferenceExit::class, 'getPackages'])->name('adm.conferenceExit.getPackages');
            Route::get('/{orderExit}/remover-pacote/{orderPackage}', [ConferenceExit::class, 'removePackage'])->name('adm.conferenceExit.removePackage');

            Route::post('/{orderExit}/adicionar-servico', [ConferenceExit::class, 'addService'])->name('adm.conferenceExit.addService');
            Route::get('/{orderExit}/listar-servico', [ConferenceExit::class, 'getServices'])->name('adm.conferenceExit.getServices');

            Route::post('/{orderExit}/imprimir-etiqueta', [ConferenceExit::class, 'printTag'])->name('adm.conferenceExit.printTag');

            Route::post('/{orderExit}/conferencia-em-lote', [ConferenceExit::class, 'conferenceInLots'])->name('adm.conferenceExit.conferenceInLots');
        });
    });

    /* Transporte */
    Route::prefix('transporte')->group(function () {

        /* Range de Cep */
        Route::prefix('range')->group(function () {
            Route::get('/', [TransportRange::class, 'index'])->name('adm.transportRange.index');
            Route::post('/', [TransportRange::class, 'save'])->name('adm.transportRange.save');
            Route::get('/listar', [TransportRange::class, 'getTransportRanges'])->name('adm.transportRange.getTransportRanges');
            Route::get('/listar/{transportRange}', [TransportRange::class, 'getTransportRange'])->name('adm.transportRange.getTransportRange');
        });

        /* Range do Parceiro */
        Route::prefix('parceiro-range')->group(function () {
            Route::get('/', [PartnerTransportRange::class, 'index'])->name('adm.partnerTransportRange.index');
            Route::post('/', [PartnerTransportRange::class, 'edit'])->name('adm.partnerTransportRange.edit');
            Route::get('/salvar/{transportRange}/{partner}', [PartnerTransportRange::class, 'save'])->name('adm.partnerTransportRange.save');
        });
    });

    /* Itens do Pedido */
    Route::prefix('pedido-itens')->group(function () {
        Route::get('/buscar-produto/{term?}', [OrderItem::class, 'findProduct'])->name('adm.orderItem.findProduct');
        Route::get('/listar', [OrderItem::class, 'getItems'])->name('adm.orderItem.getItems');
    });

    /* Destinatários */
    Route::prefix('destinatarios')->group(function () {
        Route::post('/', [Recipient::class, 'save'])->name('adm.recipient.save');
        Route::post('/autosearch', [Recipient::class, 'search'])->name('adm.recipient.search');
    });

    /* Financeiro */
    Route::prefix('financeiro')->group(function () {
        Route::get('/',                            [FinancialController::class, 'index'])->name('finan.financial.index');
        Route::get('/editar/{orden}',              [FinancialController::class, 'getOrdem'])->name('finan.financial.getOrdem');
        Route::post('/',                           [FinancialController::class, 'save'])->name('finan.financial.save');
        Route::get('/listar',                      [FinancialController::class, 'getCostCenter'])->name('finan.financial.getCostCenter');
        Route::get('/ativa-desativa/{financial}',  [FinancialController::class, 'onOff'])->name('finan.financial.onOff');

        //banco
        Route::get('/banco',                       [FinancialController::class, 'bankIndex'])->name('finan.financial.bank.index');
        Route::post('/banco',                      [FinancialController::class, 'saveBank'])->name('finan.financial.bank.save');
        Route::get('/ativa-desativa/banco/{bank}', [FinancialController::class, 'onOffBank'])->name('finan.financial.bank.onOff');
        Route::get('/listar/banco',                [FinancialController::class, 'getBank'])->name('finan.financial.bank.getBank');
        Route::get('/editar-banco/{bank}',         [FinancialController::class, 'getIdBank'])->name('finan.financial.getBank');
    });

    /* Contas a Pagar */
    Route::prefix('contas-a-pagar')->group(function () {
        Route::get('/',                           [FinancialController::class, 'indexBillsToPay'])->name('financial.bills_to_pay.index');
        Route::post('/',                          [FinancialController::class, 'saveBills'])->name('bills.save');
        Route::get('/listar',                     [FinancialController::class, 'getBills'])->name('financial.getBills');
        Route::get('/busca-custo/{costCenter?}',  [FinancialController::class, 'findCostCenter'])->name('financial.findCostCenter');
        Route::get('/busca-banco/{costBank?}',    [FinancialController::class, 'findBank'])->name('financial.findBank');
        Route::get('/editar/conta/{conta}',       [FinancialController::class, 'getConta'])->name('financial.getConta');
        Route::get('/baixar/conta/{conta}',       [FinancialController::class, 'baixarConta'])->name('financial.baixarConta');
    });

    /* Contas a receber */
    Route::prefix('contas-a-receber')->group(function () {
        Route::get('/',                           [FinancialController::class, 'indexBillsToReceive'])->name('financial.billsToReceive.index');
        Route::post('/',                          [FinancialController::class, 'saveBillsReceive'])->name('billsReceive.save');
        Route::get('/editar/conta/{conta}',       [FinancialController::class, 'getContaReceive'])->name('financial.getContaReceive');
        Route::get('/listar',                     [FinancialController::class, 'getToReceive'])->name('financial.getbillsToReceive');
        Route::get('/busca-custo/{costCenter?}',  [FinancialController::class, 'findCostCenterSint'])->name('financial.findCostCenterSint');
        Route::get('/baixar/conta/{conta}',       [FinancialController::class, 'baixarContaRece'])->name('financial.baixarContaRece');
    });

    /* Orçamentos */
    Route::prefix('orcamentos')->group(function () {
        Route::get('/',                           [FinancialController::class, 'indexBudget'])->name('budget.index');
        Route::post('/',                          [FinancialController::class, 'saveToBudget'])->name('ToBudget.save');
        Route::get('/listar',                     [FinancialController::class, 'getToBudget'])->name('budget.getBudget');
        Route::get('/editar/{id?}',               [FinancialController::class, 'getBudget'])->name('financial.getBudget');
        Route::get('/generate/ordem/{id}',        [FinancialController::class, 'generateOrdem'])->name('budget.generateOrdem');
        Route::get('/aprovar/{id}',               [FinancialController::class, 'aproveBudget'])->name('budget.aproveBudget');
        Route::get('/reprovar-ordem/{id}',        [FinancialController::class, 'reproveBudget'])->name('budget.reproveBudget');
    });

    /* Ordem de compra */
    Route::prefix('ordem-de-compra')->group(function () {
        Route::get('/',                           [FinancialController::class, 'indexPurchaseOrder'])->name('purchaseOrder.index');
        Route::get('/listar',                     [FinancialController::class, 'getToPurchaseOrder'])->name('budget.getToPurchaseOrder');
        Route::get('/editar/{id}',                [FinancialController::class, 'aproveOrdem'])->name('financial.aproveOrdem');
        Route::get('/reprovar-ordem/{id}',        [FinancialController::class, 'reproveOrdem'])->name('financial.reproveOrdem');
    });

    /* Caixinha */
    Route::prefix('caixinha')->group(function () {
        Route::get('/',                           [FinancialController::class, 'indexBox'])->name('box.index');
        Route::post('/',                          [FinancialController::class, 'saveToBox'])->name('toBox.save');
        Route::get('/busca-setor/{idSector?}',    [FinancialController::class, 'findSector'])->name('financial.findSector');
        Route::get('/listar',                     [FinancialController::class, 'getToBox'])->name('box.getToBox');
        Route::get('/listar-modal',               [FinancialController::class, 'getToBoxModal'])->name('box.getToBoxModal');
        Route::get('/fechar/{id}',                [FinancialController::class, 'closeBox'])->name('box.closeBox');
        Route::post('/repor',                     [FinancialController::class, 'resetBox'])->name('box.resetBox');
        Route::post('/retirar',                   [FinancialController::class, 'removetBox'])->name('box.removetBox');
        Route::get('/movimentacao/{id}',          [FinancialController::class, 'indexMovement'])->name('box.indexMovement');
    });

    /* Movimentações */
    Route::prefix('movimentacao')->group(function () {
        Route::get('/',                           [FinancialController::class, 'indexMove'])->name('move.index');
        Route::get('/listar',                     [FinancialController::class, 'getToMove'])->name('box.getToMove');
        // Route::post('/',                          [FinancialController::class,'saveToBox'])->name('toBox.save');
        // Route::get('/busca-setor/{idSector?}',    [FinancialController::class,'findSector'])->name('financial.findSector');
        // Route::get('/listar',                     [FinancialController::class,'getToBox'])->name('box.getToBox');
        // Route::get('/listar-modal',               [FinancialController::class,'getToBoxModal'])->name('box.getToBoxModal');
        // Route::get('/fechar/{id}',                [FinancialController::class,'closeBox'])->name('box.closeBox');
        // Route::post('/repor',                     [FinancialController::class,'resetBox'])->name('box.resetBox');
        // Route::get('/movimentacao/{id}',          [FinancialController::class,'indexMovement'])->name('box.indexMovement');
    });

    /* Relatórios */
    Route::prefix('relatorios')->group(function () {

        /* Parceiros */
        Route::prefix('parceiros')->group(function () {
            Route::get('/', [ReportPartner::class, 'index'])->name('adm.reports.partner.index');
            Route::get('/listar', [ReportPartner::class, 'getPartners'])->name('adm.reports.partner.getPartners');
        });

        /* Serviços */
        Route::prefix('servicos')->group(function () {
            Route::get('/', [ReportService::class, 'index'])->name('adm.reports.services.index');
            Route::get('/listar', [ReportService::class, 'getServices'])->name('adm.reports.services.getServices');
            Route::get('/remover-filtros', [ReportService::class, 'clearFilter'])->name('adm.reports.service.clearFilter');
            Route::post('/filtrar', [ReportService::class, 'setFilter'])->name('adm.reports.service.setFilter');
        });

        /* Estoque */
        Route::prefix('estoques')->group(function () {
            Route::get('/', [ReportStock::class, 'index'])->name('adm.reports.stock.index');
            Route::get('/listar', [ReportStock::class, 'getStocks'])->name('adm.reports.stock.getStocks');
            Route::get('/remover-filtros', [ReportStock::class, 'clearFilter'])->name('adm.reports.stock.clearFilter');
            Route::post('/filtrar', [ReportStock::class, 'setFilter'])->name('adm.reports.stock.setFilter');
        });

        /* Pedidos */
        Route::prefix('pedidos')->group(function () {

            /* Entrada */
            Route::prefix('entrada')->group(function () {
                Route::get('/', [ReportOrderEntry::class, 'index'])->name('adm.reports.orderEntry.index');
                Route::get('/listar', [ReportOrderEntry::class, 'getOrderEntries'])->name('adm.reports.orderEntry.getOrderEntries');
                Route::get('/remover-filtros', [ReportOrderEntry::class, 'clearFilter'])->name('adm.reports.orderEntry.clearFilter');
                Route::post('/filtrar', [ReportOrderEntry::class, 'setFilter'])->name('adm.reports.orderEntry.setFilter');
            });

            /* Saida */
            Route::prefix('saida')->group(function () {
                Route::get('/', [ReportOrderExit::class, 'index'])->name('adm.reports.orderExit.index');
                Route::get('/listar', [ReportOrderExit::class, 'getOrderExits'])->name('adm.reports.orderExit.getOrderExits');
                Route::get('/remover-filtros', [ReportOrderExit::class, 'clearFilter'])->name('adm.reports.orderExit.clearFilter');
                Route::post('/filtrar', [ReportOrderExit::class, 'setFilter'])->name('adm.reports.orderExit.setFilter');
            });
        });

        /* Financeiro */
        Route::prefix('financeiros')->group(function () {
            Route::get('/',                           [FinancialController::class, 'indexReport'])->name('report.index');
            Route::post('/',                          [FinancialController::class, 'saveReport'])->name('report.save');
            // Route::get('/listar',                     [FinancialController::class, 'getToReceive'])->name('financial.getbillsToReceive');
        });
    });

    /* Roles */
    Route::resource('roles', RoleController::class);
});


/* Pedidos */
Route::middleware(['auth'])->prefix('pedidos')->group(function () {

    /* Entrada */
    Route::prefix('entrada')->group(function () {
        Route::get('/', [OrderEntry::class, 'index'])->name('adm.orderEntry.index');
        Route::post('/novo', [OrderEntry::class, 'new'])->name('adm.orderEntry.new');
        Route::get('/listar', [OrderEntry::class, 'getListOrders'])->name('adm.oderEntry.getListOrders');
        Route::get('/{orderEntry}', [OrderEntry::class, 'manager'])->name('adm.orderEntry.manager');
        Route::get('/{orderEntry}/listar-items', [OrderEntry::class, 'getListOrderItems'])->name('adm.orderEntry.getListOrderItems');

        Route::post('/{orderEntry}/transporte', [OrderEntry::class, 'transport'])->name('adm.orderEntry.transport');
        Route::post('/{orderEntry}/atualizar-previsao', [OrderEntry::class, 'updateForecast'])->name('adm.orderEntry.updateForecast');
        Route::get('/{orderEntry}/cancelar', [OrderEntry::class, 'cancel'])->name('adm.orderEntry.cancel');
        Route::get('/{orderEntry}/aguardar-recebimento', [OrderEntry::class, 'receive'])->name('adm.orderEntry.receive');
        Route::post('/{orderEntry}/adicionar-item', [OrderEntry::class, 'addItem'])->name('adm.orderEntry.addItem');
        Route::get('/{orderEntry}/remover-item/{orderItem}', [OrderEntry::class, 'removeItem'])->name('adm.orderEntry.removeItem');
    });

    /* Reversa */
    Route::prefix('reversa')->group(function () {
        Route::get('/', [OrderReverse::class, 'index'])->name('adm.orderReverse.index');
        Route::get('/listar', [OrderReverse::class, 'getListOrders'])->name('adm.orderReverse.getListOrders');
    });

    /* Saida */
    Route::prefix('saida')->group(function () {
        Route::get('/', [OrderExit::class, 'index'])->name('adm.orderExit.index');
        Route::post('/novo', [OrderExit::class, 'new'])->name('adm.orderExit.new');
        Route::get('/listar', [OrderExit::class, 'getListOrders'])->name('adm.orderExit.getListOrders');
        Route::get('/{orderExit}', [OrderExit::class, 'manager'])->name('adm.orderExit.manager');
        Route::get('/{orderExit}/listar-itens', [OrderExit::class, 'getListOrderItems'])->name('adm.orderExit.getListOrderItems');

        Route::get('/{orderExit}/atualizar-previsao', [OrderExit::class, 'updateForecast'])->name('adm.orderExit.updateForecast');
        Route::get('/{orderExit}/separar', [OrderExit::class, 'breakApart'])->name('adm.orderExit.breakApart');
        Route::get('/{orderExit}/cancelar', [OrderExit::class, 'cancel'])->name('adm.orderExit.cancel');
        Route::post('/{orderExit}/destinatario', [OrderExit::class, 'recipient'])->name('adm.orderExit.recipient');
        Route::post('/{orderExit}/transporte', [OrderExit::class, 'transport'])->name('adm.orderExit.transport');
        Route::post('/{orderExit}/adicionar-item', [OrderExit::class, 'addItem'])->name('adm.orderExit.addItem');
        Route::get('/{orderExit}/remover-item/{orderItem}', [OrderExit::class, 'removeItem'])->name('adm.orderExit.removeItem');

        Route::post('/{orderExit}/adicionar-nota', [OrderExit::class, 'addNfe'])->name('adm.orderExit.addNfe');
    });
});
