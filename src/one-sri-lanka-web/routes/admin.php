<?php

use App\Http\Controllers\Admin\AlertController;
use App\Http\Controllers\Admin\CommonComplaintsController;
use App\Http\Controllers\Admin\ComplaintsCategoryController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {

    Route::get('dashboard', function () {
        return view('admin.index');
    })->name('dashboard');

    Route::prefix('common-complaints')->name('common-complaints.')->group(function () {
        Route::get('/', [CommonComplaintsController::class, 'index'])->name('index');
        Route::get('/create', [CommonComplaintsController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [CommonComplaintsController::class, 'edit'])->whereNumber('id')->name('edit');
    });

    Route::prefix('complaint-category')->name('complaint-category.')->group(function () {
        Route::get('/', [ComplaintsCategoryController::class, 'index'])->name('index');
        Route::get('/get', [ComplaintsCategoryController::class, 'get'])->name('get');
        Route::get('/show', [ComplaintsCategoryController::class, 'show'])->name('show');
        Route::get('/create', [ComplaintsCategoryController::class, 'create'])->name('create');
        Route::post('/', [ComplaintsCategoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ComplaintsCategoryController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [ComplaintsCategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [ComplaintsCategoryController::class, 'destroy'])->name('destroy');
    });

    // Alert Management Routes
    Route::prefix('alerts')->name('alerts.')->group(function () {
        Route::get('/', [AlertController::class, 'index'])->name('index');
        Route::get('/data', [AlertController::class, 'getData'])->name('data');
        Route::get('/create', [AlertController::class, 'create'])->name('create');
        Route::post('/', [AlertController::class, 'store'])->name('store');
        Route::get('/{id}', [AlertController::class, 'show'])->whereNumber('id')->name('show');
        Route::get('/{id}/edit', [AlertController::class, 'edit'])->whereNumber('id')->name('edit');
        Route::put('/{id}', [AlertController::class, 'update'])->whereNumber('id')->name('update');
        Route::delete('/{id}', [AlertController::class, 'destroy'])->whereNumber('id')->name('destroy');
        Route::post('/{id}/toggle-status', [AlertController::class, 'toggleStatus'])->whereNumber('id')->name('toggle-status');
    });


});
