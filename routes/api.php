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
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\OfertasEspecialesController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\PagosOrdenController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoDeseadoController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\PublicidadController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VarianteController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

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

//CRUD Tabla Marca
Route::prefix('marca')->group(function () {
    Route::get('/', [MarcaController::class, 'index']);
    Route::post('/', [MarcaController::class, 'store']);
    Route::get('/{id}', [MarcaController::class, 'show']);
    Route::put('/{id}', [MarcaController::class, 'update']);
    Route::delete('/{id}', [MarcaController::class, 'destroy']);
});

//CRUD Tabla Notificacion
Route::prefix('notificacion')->group(function () {
    Route::get('/', [NotificacionController::class, 'index']);
    Route::post('/', [NotificacionController::class, 'store']);
    Route::get('/{id}', [NotificacionController::class, 'show']);
    Route::put('/{id}', [NotificacionController::class, 'update']);
    Route::delete('/{id}', [NotificacionController::class, 'destroy']);
});

//CRUD Tabla OfertasEspeciales
Route::prefix('ofertasespeciales')->group(function () {
    Route::get('/', [OfertasEspecialesController::class, 'index']);
    Route::post('/', [OfertasEspecialesController::class, 'store']);
    Route::get('/{id}', [OfertasEspecialesController::class, 'show']);
    Route::put('/{id}', [OfertasEspecialesController::class, 'update']);
    Route::delete('/{id}', [OfertasEspecialesController::class, 'destroy']);
});

//CRUD Tabla Orden
Route::prefix('orden')->group(function () {
    Route::get('/', [OrdenController::class, 'index']);
    Route::post('/', [OrdenController::class, 'store']);
    Route::get('/{id}', [OrdenController::class, 'show']);
    Route::put('/{id}', [OrdenController::class, 'update']);
    Route::delete('/{id}', [OrdenController::class, 'destroy']);
});

//CRUD Tabla PagosOrden
Route::prefix('pagosorden')->group(function () {
    Route::get('/', [PagosOrdenController::class, 'index']);
    Route::post('/', [PagosOrdenController::class, 'store']);
    Route::get('/{id}', [PagosOrdenController::class, 'show']);
    Route::put('/{id}', [PagosOrdenController::class, 'update']);
    Route::delete('/{id}', [PagosOrdenController::class, 'destroy']);
});

//CRUD Tabla Producto
Route::prefix('producto')->group(function () {
    Route::get('/', [ProductoController::class, 'index']);
    Route::post('/', [ProductoController::class, 'store']);
    Route::get('/{id}', [ProductoController::class, 'show']);
    Route::put('/{id}', [ProductoController::class, 'update']);
    Route::delete('/{id}', [ProductoController::class, 'destroy']);
});

//CRUD Tabla ProductoDeseado
Route::prefix('productodeseado')->group(function () {
    Route::get('/', [ProductoDeseadoController::class, 'index']);
    Route::post('/', [ProductoDeseadoController::class, 'store']);
    Route::get('/{id}', [ProductoDeseadoController::class, 'show']);
    Route::put('/{id}', [ProductoDeseadoController::class, 'update']);
    Route::delete('/{id}', [ProductoDeseadoController::class, 'destroy']);
});

//CRUD Tabla Provincia
Route::prefix('provincia')->group(function () {
    Route::get('/', [ProvinciaController::class, 'index']);
    Route::post('/', [ProvinciaController::class, 'store']);
    Route::get('/{id}', [ProvinciaController::class, 'show']);
    Route::put('/{id}', [ProvinciaController::class, 'update']);
    Route::delete('/{id}', [ProvinciaController::class, 'destroy']);
});

//CRUD Tabla Publicidad
Route::prefix('publicidad')->group(function () {
    Route::get('/', [PublicidadController::class, 'index']);
    Route::post('/', [PublicidadController::class, 'store']);
    Route::get('/{id}', [PublicidadController::class, 'show']);
    Route::put('/{id}', [PublicidadController::class, 'update']);
    Route::delete('/{id}', [PublicidadController::class, 'destroy']);
});

//CRUD Tabla Slider
Route::prefix('slider')->group(function () {
    Route::get('/', [SliderController::class, 'index']);
    Route::post('/', [SliderController::class, 'store']);
    Route::get('/{id}', [SliderController::class, 'show']);
    Route::put('/{id}', [SliderController::class, 'update']);
    Route::delete('/{id}', [SliderController::class, 'destroy']);
});

//Crud para la tabla Role
Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::put('/{id}', [RoleController::class, 'update']);
    Route::delete('/{id}', [RoleController::class, 'destroy']);
});

//crud tabla TipoDocumento
Route::prefix('tipodocumento')->group(function () {
    Route::get('/', [TipoDocumentoController::class, 'index']);
    Route::post('/', [TipoDocumentoController::class, 'store']);
    Route::get('/{id}', [TipoDocumentoController::class, 'show']);
    Route::put('/{id}', [TipoDocumentoController::class, 'update']);
    Route::delete('/{id}', [TipoDocumentoController::class, 'destroy']);
});

//CRUD Tabla Usuario
Route::prefix('usuarios')->group(function () {
    Route::post('/login', [UsuarioController::class, 'login']);
    Route::get('/', [UsuarioController::class, 'index']);
    Route::post('/', [UsuarioController::class, 'store']);
    Route::get('/{id}', [UsuarioController::class, 'show']);
    Route::put('/{id}', [UsuarioController::class, 'update']);
    Route::delete('/{id}', [UsuarioController::class, 'destroy']);
});

//CRUD Tabla Variente
Route::prefix('variantes')->group(function () {
    Route::get('/', [VarianteController::class, 'index']);
    Route::post('/', [VarianteController::class, 'store']);
    Route::get('/{id}', [VarianteController::class, 'show']);
    Route::put('/{id}', [VarianteController::class, 'update']);
    Route::delete('/{id}', [VarianteController::class, 'destroy']);
});




