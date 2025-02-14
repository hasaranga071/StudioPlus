<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudioUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewOrderController;
use App\Http\Controllers\StudioCustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CacheController;
use App\Http\Controllers\StudioOrderTypeController;

Route::post('/cache-data', [CacheController::class, 'store']);
Route::post('/get_cached_data', [CacheController::class, 'get_cached_data']);


Route::get('/users', function () {
    return StudioUser::all(); // Correct reference
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Home Page
    Route::get('/', [HomeController::class, "index"])->name('home');

    // Orders
    Route::get('/neworder', [NewOrderController::class, "neworder"])->name('neworder');
    Route::get('/orders', [NewOrderController::class, "orders"])->name('orders');

    //OrderType
    Route::get('/neworder', [StudioOrderTypeController::class, 'newOrder'])->name('neworder');


    // Studio Users
    Route::post('/studio-user', [StudioUserController::class, 'store'])->name('studio-user.store');

    // Studio Customers
    Route::post('/customers', [StudioCustomerController::class, 'store'])->name('customers.store');
    Route::post('/customers/search', [StudioCustomerController::class, 'search'])->name('customers.search');
    Route::get('/get-customer-session', [StudioCustomerController::class, 'getCustomerSession']);
    Route::post('/set-customer-session', [StudioCustomerController::class, 'setCustomerSession']);
    Route::post('/set-order-session', [StudioCustomerController::class, 'setOrderSession']);


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route to fetch Studio details of a user
Route::get('/studiodetails_of_user', function (Request $request) {
    $param = $request->query('param'); // Get the parameter from the query string

    // Fetching data from studiousers and studios with a filter
    $data = DB::table('studiousers')
    ->join('studios', 'studiousers.studiokey', '=', 'studios.studiokey') // Joining 'orders' table
    ->select('studios.studioname','studios.studiokey') // Selecting specific columns
    ->when($param, function ($query, $param) { // Optional filter
        return $query->where('studiousers.userkey', '=', "%{$param}%");
    })
    ->get();

    return response()->json($data);
})->name('studiodetails_of_user');

// Include authentication routes
require __DIR__ . '/auth.php';


Route::post('/orders/search', [NewOrderController::class, 'search'])->name('orders.search');
