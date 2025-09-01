<?php

use App\Http\Controllers\Api\V1\Admin\ComplaintCategoryController;
use Illuminate\Support\Facades\Route;


Route::prefix('complaint-category')->name('complaint-category.')->group(function () {

    // Main CRUD Routes
    Route::get('/', [ComplaintCategoryController::class, 'index'])->name('index');
    Route::get('/create', [ComplaintCategoryController::class, 'create'])->name('create');
    Route::post('/', [ComplaintCategoryController::class, 'store'])->name('store');
    Route::get('/{id}', [ComplaintCategoryController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [ComplaintCategoryController::class, 'edit'])->name('edit');
    Route::post('/{id}/update', [ComplaintCategoryController::class, 'update'])->name('update');
    Route::patch('/{id}/update', [ComplaintCategoryController::class, 'update'])->name('patch');
    Route::delete('/{id}/delete', [ComplaintCategoryController::class, 'destroy'])->name('destroy');

    // DataTable Routes
    Route::get('/get-data', [ComplaintCategoryController::class, 'getData'])->name('get');
    Route::post('/datatable', [ComplaintCategoryController::class, 'datatable'])->name('datatable');

    // Status Management Routes
    Route::post('/{id}/toggle-status', [ComplaintCategoryController::class, 'toggleStatus'])->name('toggle-status');
    Route::post('/{id}/activate', [ComplaintCategoryController::class, 'activate'])->name('activate');
    Route::post('/{id}/deactivate', [ComplaintCategoryController::class, 'deactivate'])->name('deactivate');

    // Search and Filter Routes
    Route::get('/search', [ComplaintCategoryController::class, 'search'])->name('search');
    Route::get('/search-parents', [ComplaintCategoryController::class, 'searchParents'])->name('search-parents');
    Route::get('/filter-by-type/{type}', [ComplaintCategoryController::class, 'filterByType'])->name('filter-by-type');
    Route::get('/filter-by-status/{status}', [ComplaintCategoryController::class, 'filterByStatus'])->name('filter-by-status');

    // Additional Utility Routes
    Route::get('/get-all', [ComplaintCategoryController::class, 'getAll'])->name('get-all');
    Route::get('/get-active', [ComplaintCategoryController::class, 'getActive'])->name('get-active');
    Route::get('/get-main-categories', [ComplaintCategoryController::class, 'getMainCategories'])->name('get-main-categories');
    Route::get('/get-sub-categories', [ComplaintCategoryController::class, 'getSubCategories'])->name('get-sub-categories');
    Route::get('/get-by-parent/{parentId}', [ComplaintCategoryController::class, 'getByParent'])->name('get-by-parent');

    // Statistics and Reports Routes
    Route::get('/statistics', [ComplaintCategoryController::class, 'getStatistics'])->name('statistics');
    Route::get('/reports', [ComplaintCategoryController::class, 'getReports'])->name('reports');
    Route::get('/usage-report', [ComplaintCategoryController::class, 'getUsageReport'])->name('usage-report');
});
