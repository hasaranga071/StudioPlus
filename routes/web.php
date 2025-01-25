<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\StudioUserController;
// Route::get('/', function () {
//     return view('app.blade');
// });
Route::get('/',[HomeController::class,"index"])->name('home');
Route::get('/neworder',[StudioUserController::class,"neworder"])->name('neworder');
Route::post('/studio-user', [StudioUserController::class, 'store'])->name('studio-user.store');
