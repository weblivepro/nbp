<?php

use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\FavoriteCurrencyController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/currencies', [CurrencyController::class, 'showCurrencies']);


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/favorite-currencies', [FavoriteCurrencyController::class, 'index'])->middleware('auth');
Route::post('/favorite-currencies', [FavoriteCurrencyController::class, 'store'])->name('favorite-currencies.store')->middleware('auth');
Route::delete('/favorite-currencies/destroy-all', [FavoriteCurrencyController::class, 'destroyAll'])->name('favorite-currencies.destroy-all')->middleware('auth');
Route::delete('/favorite-currencies/{currencyCode}', [FavoriteCurrencyController::class, 'destroy'])->name('favorite-currencies.destroy')->middleware('auth');


