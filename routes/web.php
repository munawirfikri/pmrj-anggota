<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnggotaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/register', [HomeController::class, 'register'])->name('register');
Route::post('/login', [HomeController::class, 'login'])->name('login');
Route::get('/api/news', [HomeController::class, 'getNews']);
Route::post('/logout', [HomeController::class, 'logout'])->name('logout');

Route::middleware('auth:anggota')->group(function () {
    Route::get('/dashboard', [AnggotaController::class, 'dashboard'])->name('dashboard');
    Route::get('/kartu-anggota', [AnggotaController::class, 'kartuAnggota'])->name('kartu.anggota');
    Route::get('/profile', [AnggotaController::class, 'profile'])->name('profile');
    Route::put('/profile', [AnggotaController::class, 'updateProfile'])->name('profile.update');
});
