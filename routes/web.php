<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
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

Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return view('login');
    })->name('login')->middleware('notAuthenticate');  // Si no está autenticado

    Route::get('/login', function () {
        return view('login');
    })->name('login')->middleware('notAuthenticate');  // Si no está autenticado

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1', 'notAuthenticate')
        ->name('login.post');

    Route::get('/verifyCode', function () {
        return view('verifyCode');
    })->name('verifyCode')->middleware('notAuthenticate');

    Route::post('/verifyLoginCode', [AuthController::class, 'verifyLoginCode'])
        ->middleware('throttle:5,1', 'notAuthenticate')
        ->name('verifyLoginCode');

    Route::get('/register', function () {
        return view('register');
    })->name('register')->middleware('notAuthenticate');  // Si no está autenticado

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1', 'notAuthenticate')
        ->name('register.post');

    // Ruta de dashboard protegida por el middleware 'auth'
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('auth');  

    Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');
});

