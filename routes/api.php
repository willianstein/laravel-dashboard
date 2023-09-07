<?php

use App\Http\Controllers\Adm\Dashboard;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Product;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function(){

    $token = \App\Models\Partners::find(1)->createToken('teste');
    echo json_encode(['token' => $token->plainTextToken]);

});

/*
 *  PROTECTED ROUTES
 */
Route::middleware('auth:sanctum')->group(function(){

    /* Produto */
    Route::prefix('produtos')->group(function (){
        Route::get('/',[Product::class,'index']);
        Route::post('/',[Product::class,'store']);
    });

    /* Pedido */
    Route::prefix('pedidos')->group(function (){
        /* Entrada */
        Route::prefix('entradas')->group(function (){
            //Route::get('/',[OrderEntry::class,'index']);
            //Route::post('/',[OrderEntry::class,'store']);
        });
    });

});
