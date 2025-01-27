<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/Users', [UserController::class, 'getUsers']);
//     Route::post('/AddUsers', [UserController::class, 'addUser']);
//     Route::get('/User/{id}', [UserController::class, 'getUser'])->where('id', '[0-9]+'); 
//     Route::put('/UpdateUsers/{id}', [UserController::class, 'updateUser'])->where('id', '[0-9]+');
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);