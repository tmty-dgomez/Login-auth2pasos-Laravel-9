<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\XSS;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\DetectMaliciousScripts;
use App\Http\Middleware\NotAuthenticate;
use Illuminate\Console\View\Components\Mutators\EnsurePunctuation;
use App\Http\Controllers\EmailController;
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

Route::group(['web', 'detect-malicious-scripts', 'xss'], function() {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login')
            ->middleware('throttle:5,1', 'notAuthenticate',)
            ->name('login.post');

        Route::post('/verifyLoginCode', 'verifyLoginCode')
            ->middleware('throttle:5,1', 'notAuthenticate')
            ->name('verifyLoginCode');

        Route::post('/register', 'register')
            ->middleware('throttle:5,1', 'notAuthenticate')
            ->name('register.post');

        Route::post('/logout', 'logout')
            ->name('logout')
            ->middleware('auth');
    });

    Route::get('/', function () {
        return view('login');
    })->name('login')->middleware('notAuthenticate');  // Si no está autenticado

    Route::get('/login', function () {
        return view('login');
    })->name('login')->middleware('notAuthenticate');  // Si no está autenticado

    Route::get('/WelcomeNova/{userId}', [EmailController::class, 'welcomeNova'])
    ->name('WelcomeNova.index')
    ->middleware('notAuthenticate')
    ->where('userId', '[0-9]+');

    // Ruta para mostrar el formulario de verificación (firmada)
    Route::get('/verifyCode', function ($userId) {
        return view('verifyCode', ['userId' => $userId]);
    })->name('verifyCode')->middleware('signed');

    // Ruta para procesar el código de verificación (POST)
    Route::post('/verifyLoginCode', [AuthController::class, 'verifyLoginCode'])
        ->middleware('throttle:5,1', 'notAuthenticate')
        ->name('verifyLoginCode');

    Route::get('/register', function () {
        return view('register');
    })->name('register')->middleware('notAuthenticate');  

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('auth');  

    Route::fallback(function () {
        return response()->view('404', [], 404);
    });

    Route::get('/verifyEmail/{userId}', [EmailController::class, 'verifyEmail'])
    ->name('verifyEmail')
    ->middleware('signed');

});