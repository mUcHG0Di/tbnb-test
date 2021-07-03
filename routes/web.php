<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('products.index');
});

Route::post('/products/store/multiple', [ProductController::class, 'bulkStore'])
    ->name('products.store.multiple');
Route::patch('/products/update/multiple', [ProductController::class, 'bulkUpdate'])
    ->name('products.update.multiple');
Route::delete('/products', [ProductController::class, 'bulkDestroy'])
    ->name('products.destroy.multiple');
Route::get('/products/{product}/history', [ProductController::class, 'showHistory'])
    ->name('products.history');
Route::resource('/products', ProductController::class); //->parameter('product:id', 'product:uuid');

// Ajax request
Route::get('/products/{product}/get-history', [ProductController::class, 'getHistory'])
    ->name('products.history.get');

