<?php

use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\SupplierController;
use App\Http\Controllers\API\RequisitionController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register'])->middleware('auth:sanctum');
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'items', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::post('add', [ItemController::class, 'add']);
    Route::get('edit/{id}', [ItemController::class, 'edit']);
    Route::post('update/{id}', [ItemController::class, 'update']);
    Route::delete('delete/{id}', [ItemController::class, 'delete']);
});

Route::group(['prefix' => 'suppliers', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [SupplierController::class, 'index']);
    Route::post('add', [SupplierController::class, 'add']);
    Route::get('edit/{id}', [SupplierController::class, 'edit']);
    Route::post('update/{id}', [SupplierController::class, 'update']);
    Route::delete('delete/{id}', [SupplierController::class, 'delete']);
});

Route::group(['prefix' => 'requisitions', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [RequisitionController::class, 'index']);
    Route::post('add', [RequisitionController::class, 'add']);
    Route::get('edit/{id}', [RequisitionController::class, 'edit']);
    Route::post('update/{id}', [RequisitionController::class, 'update']);
    Route::post('status-change/{id}', [RequisitionController::class, 'approveOrReject']);
});

