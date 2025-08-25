<?php

use App\Http\Controllers\User\Auth\AuthController;
use App\Http\Controllers\User\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('user/dashboard', function () {
    return view('user.dashboard');
});
// Route::get('login', function () {
//     return view('user.auth.login');
// })->name('login');

Route::get('user/action', function () {
    return view('user.action');
})->name('action');

Route::get('test', [AuthController::class, 'index'])->name('test');

Route::get('user/profile', function () {
    return view('user.profile');
})->name('userProfile');


Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
});

Route::group([], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
