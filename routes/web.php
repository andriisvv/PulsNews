<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// ─── Публічні маршрути ─────────────────────────────────
Route::get('/', [NewsController::class, 'index'])->name('home');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

// ─── Інформаційні сторінки ─────────────────────────────
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/advertising', [PageController::class, 'advertising'])->name('advertising');
Route::get('/contacts', [PageController::class, 'contacts'])->name('contacts');
Route::post('/contacts', [PageController::class, 'sendContact'])->name('contacts.send');

// ─── Авторизація ───────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Адмін-панель (захищена) ───────────────────────────
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // CRUD для новин
    Route::get('/news',                [AdminController::class, 'index'])->name('news.index');
    Route::get('/news/create',         [AdminController::class, 'create'])->name('news.create');
    Route::post('/news',               [AdminController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit',    [AdminController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}',         [AdminController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}',      [AdminController::class, 'destroy'])->name('news.destroy');
});
