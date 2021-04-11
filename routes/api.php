<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\BuswayController;
use App\Http\Controllers\PlaneController;
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

    //API TRAIN
    Route::get('train', [TrainController::class, 'getAll']);
    Route::get('train/{limit}/{offset}', [TrainController::class, 'getAll']);
    Route::get('train/{id_transportation}', [TrainController::class, 'getById']);
    Route::post('train', [TrainController::class, 'insert']);
    Route::post('findTrain/{limit}/{offset}', [TrainController::class, 'find']);
    Route::put('train/{id}', [TrainController::class, 'update']);
    Route::delete('train/{id_transportation}', [TrainController::class, 'destroy']);

    //API PLANE
    Route::get('plane', [PlaneController::class, 'getAll']);
    Route::get('plane/{limit}/{offset}', [PlaneController::class, 'getAll']);
    Route::get('plane/{id_transportation}', [PlaneController::class, 'getById']);
    Route::post('plane', [PlaneController::class, 'insert']);
    Route::post('findTrain/{limit}/{offset}', [PlaneController::class, 'find']);
    Route::put('plane/{id}', [PlaneController::class, 'update']);
    Route::delete('plane/{id_transportation}', [PlaneController::class, 'destroy']);

    //API BUS
    Route::get('bus', [BuswayController::class, 'getAll']);
    Route::get('bus/{limit}/{offset}', [BuswayController::class, 'getAll']);
    Route::get('bus/{id_transportation}', [BuswayController::class, 'getById']);
    Route::post('bus', [BuswayController::class, 'insert']);
    Route::post('findTrain/{limit}/{offset}', [BuswayController::class, 'find']);
    Route::put('bus/{id}', [BuswayController::class, 'update']);
    Route::delete('bus/{id_transportation}', [BuswayController::class, 'destroy']);

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



