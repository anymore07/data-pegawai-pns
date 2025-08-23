<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\EselonController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\KotaController;

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

// Golongan Routes
Route::get('golongan', [GolonganController::class, 'index']);
Route::post('golongan/submit', [GolonganController::class, 'save']);
Route::get('golongan/delete/{id}', [GolonganController::class, 'delete']);
Route::post('golongan/all-data', [GolonganController::class, 'get_all_data']);

// Eselon Routes
Route::get('eselon', [EselonController::class, 'index']);
Route::post('eselon/submit', [EselonController::class, 'save']);
Route::get('eselon/delete/{id}', [EselonController::class, 'delete']);
Route::post('eselon/all-data', [EselonController::class, 'get_all_data']);

// Pegawai Routes
Route::get('pegawai', [PegawaiController::class, 'index']);
Route::post('pegawai/submit', [PegawaiController::class, 'save']);
Route::get('pegawai/delete/{nip}', [PegawaiController::class, 'delete']);
Route::post('pegawai/all-data', [PegawaiController::class, 'get_all_data']);
Route::get('pegawai/export-excel', [PegawaiController::class, 'export_excel']);


// Alamat Pegawai Routes
Route::get('pegawai/alamat/{nip}', [AlamatController::class, 'index']);
Route::post('alamat/save', [AlamatController::class, 'save']);
Route::get('alamat/delete/{id}', [AlamatController::class, 'delete']);
Route::post('alamat/all-data/{nip}', [AlamatController::class, 'get_all_data']);

// Kota Routes
Route::get('kota', [KotaController::class, 'index']);
Route::post('kota/submit', [KotaController::class, 'save']);
Route::get('kota/delete/{id}', [KotaController::class, 'delete']);
Route::post('kota/all-data', [KotaController::class, 'get_all_data']);