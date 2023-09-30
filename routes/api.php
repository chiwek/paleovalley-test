<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductCrudController;
use App\Http\Controllers\Auth\SanctumAuthController;
use App\Http\Controllers\Auth\SanctumLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', SanctumAuthController::class);
Route::post('/login', SanctumloginController::class);

// Route to paginate all products (public)
Route::get('products', [ProductCrudController::class, 'index']);
// Route to Read single product (public)
Route::get('products/{id}', [ProductCrudController::class, 'show']);

// Hide product CUD routes behind the auth
Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('products', [ProductCrudController::class, 'store']);
    Route::put('products/{id}', [ProductCrudController::class, 'update']);
    Route::delete('products/{id}', [ProductCrudController::class, 'destroy']);
});





