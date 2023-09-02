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
use App\Http\Controllers\DetallesCarritoController; 
use App\Http\Controllers\DetallesOrdenController; 
use App\Http\Controllers\DireccionesEntregaController; 
use App\Http\Controllers\EmpresaController; 
use App\Http\Controllers\EstadoOrdenController;
use App\Http\Controllers\FacturaElectronicaController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\InfoAdicionalController;



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
 
//CRUD Tabla DetallesCarrito
Route::prefix('detallescarrito')->group(function () {
    Route::get('/', [DetallesCarritoController::class, 'index']);
    Route::post('/', [DetallesCarritoController::class, 'store']);
    Route::get('/{id}', [DetallesCarritoController::class, 'show']);
    Route::put('/{id}', [DetallesCarritoController::class, 'update']);
    Route::delete('/{id}', [DetallesCarritoController::class, 'destroy']);
});

//CRUD Tabla DetallesOrden
Route::prefix('detallesorden')->group(function () {
    Route::get('/', [DetallesOrdenController::class, 'index']);
    Route::post('/', [DetallesOrdenController::class, 'store']);
    Route::get('/{id}', [DetallesOrdenController::class, 'show']);
    Route::put('/{id}', [DetallesOrdenController::class, 'update']);
    Route::delete('/{id}', [DetallesOrdenController::class, 'destroy']);
});

//CRUD Tabla DireccionesEntrega
Route::prefix('direccionesentrega')->group(function () {
    Route::get('/', [DireccionesEntregaController::class, 'index']);
    Route::post('/', [DireccionesEntregaController::class, 'store']);
    Route::get('/{id}', [DireccionesEntregaController::class, 'show']);
    Route::put('/{id}', [DireccionesEntregaController::class, 'update']);
    Route::delete('/{id}', [DireccionesEntregaController::class, 'destroy']);
});

//CRUD Tabla Empresa
Route::prefix('empresa')->group(function () {
    Route::get('/', [EmpresaController::class, 'index']);
    Route::post('/', [EmpresaController::class, 'store']);
    Route::get('/{id}', [EmpresaController::class, 'show']);
    Route::put('/{id}', [EmpresaController::class, 'update']);
    Route::delete('/{id}', [EmpresaController::class, 'destroy']);
});

//CRUD Tabla EstadoOrden
Route::prefix('estadoorden')->group(function () {
    Route::get('/', [EstadoOrdenController::class, 'index']);
    Route::post('/', [EstadoOrdenController::class, 'store']);
    Route::get('/{id}', [EstadoOrdenController::class, 'show']);
    Route::put('/{id}', [EstadoOrdenController::class, 'update']);
    Route::delete('/{id}', [EstadoOrdenController::class, 'destroy']);
});

//CRUD Tabla FacturaElectronica
Route::prefix('facturaelectronica')->group(function () {
    Route::get('/', [FacturaElectronicaController::class, 'index']);
    Route::post('/', [FacturaElectronicaController::class, 'store']);
    Route::get('/{id}', [FacturaElectronicaController::class, 'show']);
    Route::put('/{id}', [FacturaElectronicaController::class, 'update']);
    Route::delete('/{id}', [FacturaElectronicaController::class, 'destroy']);
});

//CRUD Tabla FormaPago
Route::prefix('formapago')->group(function () {
    Route::get('/', [FormaPagoController::class, 'index']);
    Route::post('/', [FormaPagoController::class, 'store']);
    Route::get('/{id}', [FormaPagoController::class, 'show']);
    Route::put('/{id}', [FormaPagoController::class, 'update']);
    Route::delete('/{id}', [FormaPagoController::class, 'destroy']);
});

//CRUD Tabla InfoAdicional
Route::prefix('infoadicional')->group(function () {
    Route::get('/', [InfoAdicionalController::class, 'index']);
    Route::post('/', [InfoAdicionalController::class, 'store']);
    Route::get('/{id}', [InfoAdicionalController::class, 'show']);
    Route::put('/{id}', [InfoAdicionalController::class, 'update']);
    Route::delete('/{id}', [InfoAdicionalController::class, 'destroy']);
});



