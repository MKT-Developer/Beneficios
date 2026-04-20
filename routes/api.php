<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PilarController;
use App\Http\Controllers\Api\BeneficioController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/paises/{pais}/pilares', [PilarController::class, 'index']);
Route::get('/paises/{codigo}/pilares', [PilarController::class, 'byCodigo']);

Route::get('/pilares/{slug}/beneficios', [BeneficioController::class, 'byPilar']);



// API
// Route::get('api/paises/{codigo}/pilares', [\App\Http\Controllers\Api\PilarController::class, 'byCodigo']);
// Route::get('api/pilares/{slug}/beneficios', [\App\Http\Controllers\Api\BeneficioController::class, 'byPilar']);