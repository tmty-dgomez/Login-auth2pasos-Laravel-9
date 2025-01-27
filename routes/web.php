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

 Route::get('/dashboard', function () {
     return view('dashboard');
 })->name('dashboard');

Route::get('/', function () {
        return view('login');
})->name('login'); 

Route::get('/login', function () {
    return view('login');
})->name('login'); 

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1') 
    ->name('login.post');

Route::get('/verifyCode', function () {
    return view('verifyCode');
})->name('verifyCode');

Route::post('verifyLoginCode', [AuthController::class, 'verifyLoginCode'])
    ->middleware('throttle:5,1') 
    ->name('verifyLoginCode');


Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:5,1')
    ->name('register.post');