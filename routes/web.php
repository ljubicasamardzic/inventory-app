<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentItemController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\SerialNumberController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('/users', UserController::class);
Route::get('/positions-by-department/{department}', [DepartmentController::class, 'positions']);
Route::resource('/equipment', EquipmentController::class);
Route::resource('/documents', DocumentController::class);
Route::post('/document-items/{document}', [DocumentItemController::class, 'store']);
Route::put('/document-item/return/{document_item}', [DocumentItemController::class, 'update']);
Route::put('/document-item/change-serial-number', [DocumentItemController::class, 'update_serial_number']);
Route::resource('/tickets', TicketController::class);
Route::resource('/serial-numbers', SerialNumberController::class);
Route::get('/equipment-serial-numbers/{equipment}', [EquipmentController::class, 'serial_numbers']);

// initial motion of an officer to take a request 
Route::put('/tickets/update1/{id}', [TicketController::class, 'update_1']);
// officer approves or rejects the request
Route::put('/tickets/update2/{id}', [TicketController::class, 'update_2']);
// HR approves or rejects the request
Route::put('/tickets/update3/{id}', [TicketController::class, 'update_3']);
// request is marked as finished
Route::put('/tickets/update4/{id}', [TicketController::class, 'update_4']);
Route::get('/tickets/{id}/export', [TicketController::class, 'export_order']);
Route::get('/reports', [EquipmentController::class, 'reports_index']);

Route::post('/reports/department', [EquipmentController::class, 'report_by_department']);
Route::post('/reports/position', [EquipmentController::class, 'report_by_position']);
Route::post('/reports/category', [EquipmentController::class, 'report_by_category']);
Route::post('/reports/employee', [EquipmentController::class, 'report_by_employee']);

