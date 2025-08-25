<?php

use App\Http\Controllers\Api\V1\Admin\ComplaintController;
use App\Http\Controllers\Api\V1\SampleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/welcome', [SampleController::class, 'welcome']);
Route::post('/echo', [SampleController::class, 'echoRequest']);


Route::prefix('complaint-category')->name('complaint-category.')->group(function () {
    Route::get('/', [ComplaintController::class, 'index'])->name('index');
    Route::post('/', [ComplaintController::class, 'storeComplaintCategory'])->name('store');
    Route::get('/{id}/edit', [ComplaintController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ComplaintController::class, 'update'])->name('update');
    Route::delete('/{id}', [ComplaintController::class, 'destroy'])->name('destroy');
});
