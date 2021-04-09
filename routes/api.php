<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransportationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register', [LoginController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

Route::group(['middleware' => ['jwt.verify:admin,user']], function () {
    Route::get('login/check', [LoginController::class,'loginCheck']);
    Route::post('logout', [LoginController::class,'logout']);   
});

// Route::group(['middleware' => ['jwt.verify: admin']], function () {

    //API TRANSACTION
    Route::get('transaction', [TransactionController::class, 'getAllTransaction']);
    Route::get('transaction/{limit}/{offset}', [TransactionController::class, 'getAllTransaction']);
    Route::get('transaction/{id}', [TransactionController::class, 'getById']);
    Route::put('transaction/status/{id}', [TransactionController::class, 'changeStatus']);
    Route::post('transaction', [TransactionController::class, 'insert']);
    Route::delete('transaction/{id}', [TransactionController::class, 'destroy']);

    //API TRANSPORTATION
    Route::get('transportation', [TransportationController::class, 'getAllTransportation']);
    Route::get('transportation/{limit}/{offset}', [TransportationController::class, 'getAllTransportation']);
    Route::get('transportation/{id_transportation}', [TransportationController::class, 'getById']);
    Route::post('transportation', [TransportationController::class, 'insert']);
    Route::post('findTransportation/{limit}/{offset}', [TransportationController::class, 'find']);
    Route::put('transportation/{id}', [TransportationController::class, 'update']);
    Route::delete('transportation/{id_transportation}', [TransportationController::class, 'destroy']);

    //API USER
    Route::get('user', [UserController::class, 'getAll']);
    Route::get('user/{id}', [UserController::class, 'getById']);
    Route::post('user', [UserController::class, 'insert']);
    Route::put('user/{id}', [UserController::class, 'edit']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

    //API CATEGORY
    Route::get('category', [CategoryController::class, 'getAll']);
    Route::get('category/{id}', [CategoryController::class, 'getById']);
    Route::delete('category/{id}', [CategoryController::class, 'destroy']);
// });



