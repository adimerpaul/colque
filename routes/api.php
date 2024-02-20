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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//impuestos
Route::post('emitirExportacionMineral', [\App\Http\Controllers\ExportacionMineralController::class, 'emitirExportacionMineral']);
Route::get('getCompraVenta', [\App\Http\Controllers\CompraVentaController::class, 'index']);
Route::get('getExportacionMineral', 'ExportacionMineralController@index');
Route::post('emitirCompraVenta', [\App\Http\Controllers\CompraVentaController::class, 'emitirCompraVenta']);
Route::post('anulacionCompraVenta', 'CompraVentaController@anulacionCompraVenta');
Route::post('anularExportacionMineral', 'ExportacionMineralController@anularExportacionMineral');


Route::get('lista-minerales', [\App\Http\Controllers\PruebaMineralController::class ,'getMinerales']);
Route::post('registrar-mineral', [\App\Http\Controllers\PruebaMineralController::class, 'registrar']);
//Route::delete('eliminar-mineral/{id}', [\App\Http\Controllers\PruebaMineralController::class, 'eliminar']);


Route::get('get-compras', [\App\Http\Controllers\AndroidLaboratorioController::class, 'getCompras']);
Route::post('guardar-laboratorio', [\App\Http\Controllers\AndroidLaboratorioController::class, 'storeEstanio']);
Route::post('guardar-estanio', [\App\Http\Controllers\Lab\EnsayoController::class, 'storeEstanio']);
Route::post('guardar-plata', [\App\Http\Controllers\Lab\EnsayoController::class, 'storePlata']);
Route::post('guardar-humedad', [\App\Http\Controllers\AndroidLaboratorioController::class, 'storeHumedad']);
Route::post('guardar-humedad-nuevo', [\App\Http\Controllers\Lab\EnsayoController::class, 'storeHumedad']);
Route::get('get-ensayos-nuevo', [\App\Http\Controllers\Lab\EnsayoController::class, 'getEnsayos']);
Route::get('get-ensayos', [\App\Http\Controllers\AndroidLaboratorioController::class, 'getEnsayos']);

Route::get('get-cantidad-proceso', [\App\Http\Controllers\AndroidLaboratorioController::class, 'getCantidadProceso']);
Route::post('guardar-ensayo', [\App\Http\Controllers\AndroidLaboratorioController::class, 'storeEnsayo']);
Route::post('cambiar-estado', [\App\Http\Controllers\AndroidLaboratorioController::class, 'cambiarEstado']);
Route::get('informe-ensayo/{id}', [\App\Http\Controllers\LaboratorioController::class ,'imprimirInforme']);


Route::get('get-ubicaciones', [\App\Http\Controllers\UbicacionController::class, 'lista']);
Route::get('buscar-ubicaciones/{id}', [\App\Http\Controllers\UbicacionController::class, 'buscarUbicacion']);
Route::get('get-lotes', [\App\Http\Controllers\UbicacionController::class, 'getLotes']);
Route::get('lotes-por-ubicacion/{id}', [\App\Http\Controllers\UbicacionController::class, 'getLotesDeCuadro']);
Route::post('agregar-ubicacion', [\App\Http\Controllers\UbicacionController::class, 'agregar']);
Route::post('mover-lote', [\App\Http\Controllers\UbicacionController::class, 'mover']);
//Route::post('registrar-elemento', [\App\Http\Controllers\AndroidLaboratorioController::class, 'storeElemento']);



Route::apiresource('choferes', ChoferController::class);
