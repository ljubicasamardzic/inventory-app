<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentItemController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\SerialNumberController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('/users', UserController::class);
Route::get('/positions-by-department/{department}', [DepartmentController::class, 'positions']);
Route::resource('/equipment', EquipmentController::class);
Route::resource('/documents', DocumentController::class);
Route::post('/document-items/{document}', [DocumentItemController::class, 'store']);
Route::put('/document-item/return/{document_item}', [DocumentItemController::class, 'update']);
Route::resource('/tickets', TicketController::class);
Route::resource('/serial-numbers', SerialNumberController::class);
Route::get('/equipment-serial-numbers/{equipment}', [EquipmentController::class, 'serial_numbers']);


