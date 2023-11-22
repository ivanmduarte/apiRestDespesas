<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('registrar', 'App\Http\Controllers\api\AuthController@register');

Route::post('login', 'App\Http\Controllers\api\AuthController@login');

Route::middleware('auth:sanctum')->apiResource('despesa', 'App\Http\Controllers\api\DespesaController');
