<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;

Route::get('/', [DashboardController::class, 'index']);

Route::get('jabatan', [JabatanController::class, 'index']);
Route::post('jabatan/submit', [JabatanController::class, 'save']);
Route::get('jabatan/delete/{id}', [JabatanController::class, 'delete']);
Route::post('jabatan/all-data', [JabatanController::class, 'get_all_data']);