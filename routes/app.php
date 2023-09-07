<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Ticket as AppTicket;


Route::middleware(['auth', 'can:type-user-app'])->prefix('app')->group(function(){

    /* DashBoard */
    Route::get('/',[\App\Http\Controllers\App\Dashboard::class,'index'])->name('app.dashboard.index');

    /* Tickets */
    Route::prefix('tickets')->group(function () {

        Route::get('/', [AppTicket::class, 'index'])->name('app.ticket.index');
        Route::post('/', [AppTicket::class, 'save'])->name('app.ticket.save');
        Route::get('/listar', [AppTicket::class, 'getTickets'])->name('app.ticket.getTickets');
        Route::get('/gerenciar/{ticket}', [AppTicket::class, 'manager'])->name('app.ticket.manager');
        Route::post('/gerenciar/{ticket}/nova-mensagem', [AppTicket::class, 'sendMessage'])->name('app.ticket.sendMessage');

    });

});

Route::middleware(['auth'])->group(function () {
    /* Configurações */
    Route::prefix('configuracoes')->group(function (){
        Route::get('/',[\App\Http\Controllers\App\MyConfig::class,'index'])->name('app.myConfig.index');

        /* Tokens */
        Route::prefix('token')->group(function (){
            Route::post('/',[\App\Http\Controllers\App\Token::class,'save'])->name('app.token.save');
            Route::get('/',[\App\Http\Controllers\App\Token::class,'index'])->name('app.token.index');
            Route::get('/listar/{partner?}',[\App\Http\Controllers\App\Token::class,'getTokens'])->name('app.token.getTokens');
            Route::get('/excluir/{partner}/{token_id}',[\App\Http\Controllers\App\Token::class,'deleteToken'])->name('app.token.deleteToken');
        });

        /* Integrações */
        Route::prefix('integracoes')->group(function (){
            Route::get('/',[\App\Http\Controllers\App\Integration::class,'index'])->name('app.integration.index');
            Route::post('/',[\App\Http\Controllers\App\Integration::class,'save'])->name('app.integration.save');

            /* Horus */
            Route::prefix('horus')->group(function (){
                Route::get('/',[\App\Http\Controllers\App\Integrations\HorusConfig::class,'index'])->name('app.horusConfig.index');
            });

        });

    });
});
