<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CarritoCompraController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\ClienteController; 
use App\Http\Controllers\ComentarioResenaController; 
use App\Http\Controllers\DatosFacturacionController; 

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



//CRUD Tabla Banner
Route::prefix('banners')->group(function () {
    Route::get('/', [BannerController::class, 'index']);
    Route::post('/', [BannerController::class, 'store']);
    Route::get('/{id}', [BannerController::class, 'show']);
    Route::put('/{id}', [BannerController::class, 'update']);
    Route::delete('/{id}', [BannerController::class, 'destroy']);
});

//CRUD Tabla CarritoCompra
Route::prefix('carritocompras')->group(function () {
    Route::get('/', [CarritoCompraController::class, 'index']);
    Route::post('/', [CarritoCompraController::class, 'store']);
    Route::get('/{id}', [CarritoCompraController::class, 'show']);
    Route::put('/{id}', [CarritoCompraController::class, 'update']);
    Route::delete('/{id}', [CarritoCompraController::class, 'destroy']);
});


//CRUD Tabla Categoria
Route::prefix('categorias')->group(function () {
    Route::get('/', [CategoriaController::class, 'index']);
    Route::post('/', [CategoriaController::class, 'store']);
    Route::get('/{id}', [CategoriaController::class, 'show']);
    Route::put('/{id}', [CategoriaController::class, 'update']);
    Route::delete('/{id}', [CategoriaController::class, 'destroy']);
});


//CRUD Tabla Ciudad
Route::prefix('ciudades')->group(function () {
    Route::get('/', [CiudadController::class, 'index']);
    Route::post('/', [CiudadController::class, 'store']);
    Route::get('/{id}', [CiudadController::class, 'show']);
    Route::put('/{id}', [CiudadController::class, 'update']);
    Route::delete('/{id}', [CiudadController::class, 'destroy']);
});


//CRUD Tabla Cliente
Route::prefix('clientes')->group(function () {
    Route::get('/', [ClienteController::class, 'index']);
    Route::post('/', [ClienteController::class, 'store']);
    Route::get('/{id}', [ClienteController::class, 'show']);
    Route::put('/{id}', [ClienteController::class, 'update']);
    Route::delete('/{id}', [ClienteController::class, 'destroy']);
});


//CRUD Tabla ComentarioResena
Route::prefix('comentarioresena')->group(function () {
    Route::get('/', [ComentarioResenaController::class, 'index']);
    Route::post('/', [ComentarioResenaController::class, 'store']);
    Route::get('/{id}', [ComentarioResenaController::class, 'show']);
    Route::put('/{id}', [ComentarioResenaController::class, 'update']);
    Route::delete('/{id}', [ComentarioResenaController::class, 'destroy']);
});


//CRUD Tabla DatosFacturacion
Route::prefix('datosfacturacion')->group(function () {
    Route::get('/', [DatosFacturacionController::class, 'index']);
    Route::post('/', [DatosFacturacionController::class, 'store']);
    Route::get('/{id}', [DatosFacturacionController::class, 'show']);
    Route::put('/{id}', [DatosFacturacionController::class, 'update']);
    Route::delete('/{id}', [DatosFacturacionController::class, 'destroy']);
});


