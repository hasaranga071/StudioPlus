<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\StudioUserController;
// Route::get('/', function () {
//     return view('app.blade');
// });
Route::get('/',[HomeController::class,"index"])->name('home');
Route::get('/newuser',[StudioUserController::class,"newuser"])->name('newuser');
#Route::post('/studiouser', [StudioUserController::class, 'store'])->name('studiouser.store');
Route::post('/studiouser/store', [StudioUserController::class, 'store'])->name('studioUser.store');
