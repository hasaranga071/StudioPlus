<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\NewOrderController;
// Route::get('/', function () {
//     return view('app.blade');
// });
Route::get('/',[HomeController::class,"index"])->name('home');
Route::get('/neworder',[NewOrderController::class,"neworder"])->name('neworder');