<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalcController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/login' , [LoginController::class , 'login']); // ログインAPI

Route::get('/calcParabolicMotion' , [CalcController::class , 'parabolicMotion']); // 放物運動計算API
Route::get('/parabolicMotionPython' , [CalcController::class , 'parabolicMotionPython']); // 放物運動計算API(Python)
Route::get('/outerApiTest' , [CalcController::class , 'outerApiTest']); // 放物運動計算API