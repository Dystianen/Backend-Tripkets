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

Route::group(['middleware' => ['jwt.verify:admin']], function () {
    //API CATEGORY
    Route::get('category', [CategoryController::class,'getAll']);
    Route::get('category/{id}', [CategoryController::class,'getByID']);

    //API TRANSACTION
    Route::get('transaction', [TransactionController::class, 'getAllTransaction']);
    Route::get('transaction/{limit}/{offset}', [TransactionController::class, 'getAllTransaction']);
    Route::get('transaction/{id}', [TransactionController::class, 'getById']);
    Route::post('transaction/status/{id}', [TransactionController::class, 'changeStatus']);
    Route::post('transaction', [TransactionController::class, 'insert']);

    //API USER
    Route::get('user', [UserController::class, 'getAll']);
    Route::get('user/{id}', [UserController::class, 'getById']);
    Route::post('user/insert', [UserController::class, 'insert']);
    Route::post('user/update', [UserController::class, 'update']);
    Route::post('user/{id}', [UserController::class, 'destroy']);
});

Route::group(['middleware' => ['jwt.verify:user']], function () {
    //API USER
    Route::get('user', [UserController::class, 'getAll']);
    Route::get('user/{limit}/{offset}', [UserController::class, 'getAll']);
    Route::post('user/{id}', [UserController::class, 'getById']);
    Route::post('user/update/{id}', [UserController::class, 'store']);

    //API TRANSACTION
    Route::get('transaction', [TransactionController::class, 'getAllTransaction']);
    Route::get('transaction/{limit}/{offset}', [TransactionController::class, 'getAllTransaction']);
    Route::get('transaction/{id}', [TransactionController::class, 'getById']);
    Route::post('transaction', [TransactionController::class, 'insert']);
    Route::post('transaction/status', [TransactionController::class, 'changeStatus']);
    Route::delete('transaction/{id}', [TransactionController::class, 'destroy']);

    //API TRANSPORTATION
    Route::get('transportation', [TransportationController::class, 'getAllTransportation']);
    Route::get('transportation/{limit}/{offset}', [TransportationController::class, 'getAllTransportation']);
    Route::get('transportation/{id}', [TransportationController::class, 'getById']);
    Route::post('transportation/insert', [TransportationController::class, 'insert']);
    Route::post('transportation/update', [TransportationController::class, 'update']);
    Route::delete('transportation/{id}', [TransportationController::class, 'delete']);

    //API CATEGORY
    Route::get('category', [CategoryController::class, 'getAll']);
    Route::get('category/{id}', [CategoryController::class, 'getById']);
    Route::delete('category/{id}', [CategoryController::class, 'destroy']);
});


