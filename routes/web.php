<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController, JabatanController, UnitKerjaController,
    GolonganController, EselonController, PegawaiController,
    AlamatController, KotaController, AuthController, ArticleToolController
};

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post')->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Jabatan
    Route::get('jabatan', [JabatanController::class, 'index']);
    Route::post('jabatan/submit', [JabatanController::class, 'save']);
    Route::get('jabatan/delete/{id}', [JabatanController::class, 'delete']);
    Route::post('jabatan/all-data', [JabatanController::class, 'get_all_data']);

    // Unit Kerja
    Route::get('unit-kerja', [UnitKerjaController::class, 'index']);
    Route::post('unit-kerja/submit', [UnitKerjaController::class, 'save']);
    Route::get('unit-kerja/delete/{id}', [UnitKerjaController::class, 'delete']);
    Route::post('unit-kerja/all-data', [UnitKerjaController::class, 'get_all_data']);

    // Golongan
    Route::get('golongan', [GolonganController::class, 'index']);
    Route::post('golongan/submit', [GolonganController::class, 'save']);
    Route::get('golongan/delete/{id}', [GolonganController::class, 'delete']);
    Route::post('golongan/all-data', [GolonganController::class, 'get_all_data']);

    // Eselon
    Route::get('eselon', [EselonController::class, 'index']);
    Route::post('eselon/submit', [EselonController::class, 'save']);
    Route::get('eselon/delete/{id}', [EselonController::class, 'delete']);
    Route::post('eselon/all-data', [EselonController::class, 'get_all_data']);

    // Pegawai
    Route::get('pegawai', [PegawaiController::class, 'index']);
    Route::post('pegawai/submit', [PegawaiController::class, 'save']);
    Route::get('pegawai/delete/{nip}', [PegawaiController::class, 'delete']);
    Route::post('pegawai/all-data', [PegawaiController::class, 'get_all_data']);
    Route::get('pegawai/export-excel', [PegawaiController::class, 'export_excel']);

    // Alamat Pegawai
    Route::get('pegawai/alamat/{nip}', [AlamatController::class, 'index']);
    Route::post('alamat/save', [AlamatController::class, 'save']);
    Route::get('alamat/delete/{id}', [AlamatController::class, 'delete']);
    Route::post('alamat/all-data/{nip}', [AlamatController::class, 'get_all_data']);

    // Kota
    Route::get('kota', [KotaController::class, 'index']);
    Route::post('kota/submit', [KotaController::class, 'save']);
    Route::get('kota/delete/{id}', [KotaController::class, 'delete']);
    Route::post('kota/all-data', [KotaController::class, 'get_all_data']);

    // Logout (tetap di area auth)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});



Route::get('/artikel-tool', [ArticleToolController::class, 'index']);
Route::post('/artikel-tool/run', [ArticleToolController::class, 'run'])->name('artikel.run');