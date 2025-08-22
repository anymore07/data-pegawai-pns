<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\UnitKerjaController;

// Web Route
Route::get('/', [DashboardController::class, 'index']);

//Jabatan Routes
Route::get('jabatan', [JabatanController::class, 'index']);
Route::post('jabatan/submit', [JabatanController::class, 'save']);
Route::get('jabatan/delete/{id}', [JabatanController::class, 'delete']);
Route::post('jabatan/all-data', [JabatanController::class, 'get_all_data']);

// Unit Kerja Routes
Route::get('unit-kerja', [UnitKerjaController::class, 'index']);
Route::post('unit-kerja/submit', [UnitKerjaController::class, 'save']);
Route::get('unit-kerja/delete/{id}', [UnitKerjaController::class, 'delete']);
Route::post('unit-kerja/all-data', [UnitKerjaController::class, 'get_all_data']);
