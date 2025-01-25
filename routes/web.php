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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/bienvenida/{userId}', [WelcomeController::class, 'bienvenida'])
    ->name('bienvenida.index');

Route::get('/', function () {
        return view('login');
})->name('login');

Route::get('/login', function () {
    return view('login');
})->middleware('guest')->name('login'); 

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest', 'throttle:5,1') 
    ->name('login.post');

Route::get('/verifyCode', function () {
    return view('verifyCode');
})->middleware('guest','throttle:5,1')->name('verifyCode');

Route::post('verifyLoginCode', [AuthController::class, 'verifyLoginCode'])
    ->middleware('guest', 'throttle:5,1') 
    ->name('verifyLoginCode');


Route::get('/register', function () {
    return view('register');
})->middleware('guest')->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest', 'throttle:5,1')
    ->name('register.post');