<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrdersController;
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

Route::get('/', [OrdersController::class, 'index']);

Route::get('/cart', [OrdersController::class, 'showCart'])->name('cart.show');
Route::post('/cart/update', [OrdersController::class, 'updateCart'])->name('cart.update');
Route::get('/cart/shipment', [OrdersController::class, 'shipment'])->name('cart.show');

Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);
Route::get('/admin/dashboard', [AdminController::class, 'ordersReport']);
Route::get('/admin/orders/{orderId}', [AdminController::class, 'orderDetails'])->name('admin.orders.details');



Route::get('/test-database', function () {
    try {
        DB::connection()->getPdo();
        echo "Connected successfully to the database!";
    } catch (\Exception $e) {
        die("Could not connect to the database. Error: " . $e->getMessage());
    }
});
