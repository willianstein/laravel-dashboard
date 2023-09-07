<?php

use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('/')->group(function () {
    Route::get('/', [Dashboard::class, 'index'])->name('adm.dashboard.index');
    Route::get('/dashboards/orderTracking', [Dashboard::class, 'getBanner'])->name('adm.dashboard.getBanner');
    Route::get('/dashboards/orderTracking/data', [Dashboard::class, 'getBannerData'])->name('adm.dashboard.getBannerData');
});
