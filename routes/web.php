<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KodeTokoController;
use App\Http\Controllers\NominalController;
use App\Http\Controllers\AreaSalesController;
use App\Http\Controllers\SalesController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Tabel A - Kode Toko
Route::prefix('kode_toko')->group(function () {
    Route::get('view_table_kode_toko', [KodeTokoController::class, 'index'])->name('view.toko');
    Route::post('create_table_kode_toko', [KodeTokoController::class, 'createToko'])->name('create.toko');
    Route::delete('destroy_table_kode_toko/{id}', [KodeTokoController::class, 'destroyToko'])->name('destroy.toko');
    Route::post('update_table_kode_toko/{id}', [KodeTokoController::class, 'updateToko'])->name('update.toko');
    Route::post('import_table_kode_toko', [KodeTokoController::class, 'importToko'])->name('import.toko');
});

// Tabel B - Nominal
Route::prefix('nominal')->group(function () {
    Route::get('view_table_nominal', [NominalController::class, 'index'])->name('view.nominal');
    Route::post('create_table_nominal', [NominalController::class, 'createNominal'])->name('create.nominal');
    Route::delete('destroy_table_nominal/{id}', [NominalController::class, 'destroyNominal'])->name('destroy.nominal');
    Route::post('update_table_nominal/{id}', [NominalController::class, 'updateNominal'])->name('update.nominal');
    Route::post('import_table_nominal', [NominalController::class, 'importNominal'])->name('import.nominal');
});

// Tabel C - Area Sales
Route::prefix('area-sales')->group(function () {
    Route::get('view_table_area_sales', [AreaSalesController::class, 'index'])->name('view.area_sales');
    Route::post('create_table_area_sales', [AreaSalesController::class, 'createAreaSales'])->name('create.area_sales');
    Route::delete('destroy_table_area_sales/{id}', [AreaSalesController::class, 'destroyAreaSales'])->name('destroy.area_sales');
    Route::post('update_table_area_sales/{id}', [AreaSalesController::class, 'updateAreaSales'])->name('update.area_sales');
    Route::post('import_table_area_sales', [AreaSalesController::class, 'importAreaSales'])->name('import.area_sales');
});

// Tabel D - Sales
Route::prefix('sales')->group(function () {
    Route::get('view_table_sales', [SalesController::class, 'index'])->name('view.sales');
    Route::post('create_table_sales', [SalesController::class, 'createSales'])->name('create.sales');
    Route::delete('destroy_table_sales/{id}', [SalesController::class, 'destroySales'])->name('destroy.sales');
    Route::post('update_table_sales/{id}', [SalesController::class, 'updateSales'])->name('update.sales');
    Route::post('import_table_sales', [SalesController::class, 'importSales'])->name('import.sales');
});
