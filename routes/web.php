<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::controller(ImportController::class)->group(function () {
    Route::post('/import', 'import')->name('user.import');
});

Route::get('/', [SaleController::class,'index'])->name('upload');
Route::post('/', [SaleController::class,'upload_csv_records']);
