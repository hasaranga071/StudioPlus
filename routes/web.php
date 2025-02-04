<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudioUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewOrderController;
use App\Http\Controllers\StudioCustomerController;

 Route::middleware('auth')->group(function () {
    Route::get('/',[HomeController::class,"index"])->name('home');
    Route::get('/neworder',[NewOrderController::class,"neworder"])->name('neworder');
    Route::post('/studio-user', [StudioUserController::class, 'store'])->name('studio-user.store');
    Route::get('/orders',[NewOrderController::class,"orders"])->name('orders');
    //Route::get('/neworder',[StudioUserController::class,"neworder"])->name('neworder');
    //Route::post('/studio-user', [StudioUserController::class, 'store'])->name('studio-user.store');
    Route::post('/customers', [StudioCustomerController::class, 'store'])->name('customers.store');

});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
