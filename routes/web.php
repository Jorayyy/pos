<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POSController;

Route::get('/', [POSController::class, 'index'])->name('pos.index');
Route::post('/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
Route::post('/products', [POSController::class, 'storeProduct'])->name('products.store');
Route::delete('/products/{id}', [POSController::class, 'destroyProduct'])->name('products.destroy');

// Debt Credit Routing Rules
Route::post('/credits', [POSController::class, 'storeCredit'])->name('credits.store');
Route::post('/credits/{id}/toggle', [POSController::class, 'toggleCredit'])->name('credits.toggle');
Route::delete('/credits/{id}', [POSController::class, 'destroyCredit'])->name('credits.destroy');

Route::put('/products/{id}', [POSController::class, 'updateProduct'])->name('products.update');
