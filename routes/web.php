<?php

use App\Http\Controllers\Auth\StaffAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PublicCatalogController;

Route::get('/', function () {
    return redirect()->route('katalog.index');
});

Route::get('/katalog', [PublicCatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog/{book}', [PublicCatalogController::class, 'show'])->name('katalog.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [StaffAuthController::class, 'create'])->name('login');
    Route::post('/login', [StaffAuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [StaffAuthController::class, 'destroy'])->name('logout');

    Route::resource('books', BookController::class);
    Route::resource('members', MemberController::class)->except(['show']);

    Route::patch('loans/{loan}/return', [LoanController::class, 'markReturned'])->name('loans.return');
    Route::resource('loans', LoanController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
});
