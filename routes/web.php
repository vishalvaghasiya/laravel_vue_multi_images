<?php


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

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'post'], function () {
    Route::get('/fetch', [PostController::class, 'index'])->name('post');
    Route::post('/store', [PostController::class, 'store'])->name('store');
});

Route::get('/{any}', function () {
    return view('layouts.app');
})->where('any', '.*');
