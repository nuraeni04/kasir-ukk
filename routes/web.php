<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;




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


Route::get('/', function() {
    return view('layouts.app');
});

Route::middleware('auth')->get('/', [HomeController::class, 'index'])->name('index');

// User
Route::middleware(['auth'])->prefix('/users')->name('users.')->group(function() {
    Route::get('/', [UsersController::class, 'index'])->name('index');
    Route::get('/create', [UsersController::class, 'create'])->name('create');
    Route::post('/store', [UsersController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [UsersController::class, 'edit'])->name('edit');
    Route::patch('/update/{id}', [UsersController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [UsersController::class, 'destroy'])->name('delete');

});

Route::middleware(['auth'])->prefix('/products')->name('products.')->group(function() {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/store', [ProductController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
    Route::patch('/update/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('delete');
    Route::get('/editStock/{id}', [ProductController::class, 'editStock'])->name('editStock');
    Route::patch('/updateStock/{id}', [ProductController::class, 'updateStock'])->name('updateStock');
});

Route::middleware(['auth'])->prefix('/sale')->name('sale.')->group(function() {
    Route::get('/', [SaleController::class, 'index'])->name('index');
    Route::get('/create', [SaleController::class, 'create'])->name('create');
    Route::post('/store', [SaleController::class, 'store'])->name('store');
    Route::get('/detail-print/{saleId}', [SaleController::class, 'detailPrint'])->name('detail-print');
    Route::get('/download-excel', [SaleController::class, 'downloadExcel'])->name('download-excel');
    Route::get('/pesan', function() {
        return view('pembelian-admin.penjualan');
    })->name('pembelian-admin.penjualan');
    Route::get('/member/{saleId}', [SaleController::class, 'member'])->name('member');
    Route::patch('/update/{Id}', [SaleController::class, 'update'])->name('update');
    Route::get('/dialog/{Id}', [SaleController::class, 'dialog'])->name('dialog');
});

Auth::routes();

Route::get('/generate-password', function() {
    return Hash::make("admin123");
});

Route::get('/sale/{id}/download', [SaleController::class, 'download'])->name('sale.download');





