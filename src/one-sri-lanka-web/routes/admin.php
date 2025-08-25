<?php

use App\Http\Controllers\Admin\CommonComplaintsController;
use App\Http\Controllers\Admin\ComplaintsCategoryController;
use App\Http\Controllers\Admin\ComplaintsController;
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
        Route::get('/create', [ComplaintsCategoryController::class, 'create'])->name('create');
        Route::post('/', [ComplaintsCategoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ComplaintsCategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ComplaintsCategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [ComplaintsCategoryController::class, 'destroy'])->name('destroy');
    });
});
