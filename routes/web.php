<?php

use App\Models\Bono;
use App\Models\Lab\Recepcion;
use App\Models\PagoMovimiento;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



//Route::resource('tipoCambios', App\Http\Controllers\TipoCambioController::class);
//Route::resource('empresaMinerales', App\Http\Controllers\EmpresaMineralController::class);


Route::resource('leys', App\Http\Controllers\LeyController::class, ['except' => ['show', 'index', 'create']]);
Route::get('leys/lista/{id}', 'App\Http\Controllers\LeyController@lista')->name('leys.lista');
Route::get('leys/register/{id}', 'App\Http\Controllers\LeyController@register')->name('leys.register');


//Route::resource('users', App\Http\Controllers\UserController::class, ['only' => 'show']);
Route::get('perfil', 'App\Http\Controllers\UserController@perfil')->name('users.perfil');



//Route::resource('formularioLiquidacions', App\Http\Controllers\FormularioLiquidacionController::class);




Route::group(['middleware' => ['administrador']], function () {
    //Route::resource('clientes', App\Http\Controllers\ClienteController::class, ['except' => ['show', 'create', 'index']]);
    Route::get('cambiar-docs', 'App\Http\Controllers\CajaController@cambiarNombreDocs');
    Route::get('cambiar-docs-complejo', 'App\Http\Controllers\CajaController@cambiarNombreDocsComplejo');
    Route::delete('eliminar-retencion/{id}', [\App\Http\Controllers\RetencionPagoController::class, 'eliminar'])->name('eliminar-retencion');


    Route::resource('empresas', App\Http\Controllers\EmpresaController::class);

    Route::resource('personals', App\Http\Controllers\PersonalController::class, ['except' => ['show']]);

    Route::resource('accesos', App\Http\Controllers\AccesoController::class);

    Route::resource('materials', App\Http\Controllers\MaterialController::class, ['except' => ['show', 'destroy']]);

    Route::resource('tipoCambios', App\Http\Controllers\TipoCambioController::class, ['except' => ['show', 'destroy']]);

    Route::resource('productos', App\Http\Controllers\ProductoController::class);
    Route::resource('producto_minerales', \App\Http\Controllers\ProductoMineralController::class);

    Route::get('productos-minerales/editar-ley/{id}/{valor}', [\App\Http\Controllers\ProductoMineralController::class, 'updateLey']);

    Route::resource('tablaAcopiadoras', App\Http\Controllers\TablaAcopiadoraController::class);
    Route::get('seleccionar/{tablaAcopiadora}', [App\Http\Controllers\TablaAcopiadoraController::class, 'seleccionar'])->name('tablaAcopiadoras.seleccionar');

    Route::resource('contratos', App\Http\Controllers\ContratoController::class, ['except' => ['destroy']]);
    Route::resource('bases-plomo-plata', App\Http\Controllers\BasePlomoPlataController::class, ['only' => ['index', 'store', 'destroy']]);
    Route::resource('terminos-plomo-plata', App\Http\Controllers\TerminosPlomoPlataController::class, ['only' => ['index', 'store', 'destroy']]);

    Route::resource('laboratorioQuimicos', App\Http\Controllers\LaboratorioQuimicoController::class, ['except' => ['destroy', 'show']]);
    Route::resource('laboratorioPrecios', App\Http\Controllers\LaboratorioPrecioController::class, ['only' => ['index', 'edit', 'update']]);

    Route::get('get-lotes-activos/{letra}', [\App\Http\Controllers\FormularioLiquidacionController::class, 'getLotesActivos']);
    Route::post('intercambiar-lotes', [\App\Http\Controllers\FormularioLiquidacionController::class, 'intercambiarLote'])->name('formularios.intercambiar');
    Route::resource('prestamo', App\Http\Controllers\PrestamoController::class, ['only' => ['show']]);
    Route::resource('terceros', App\Http\Controllers\MovimientoController::class, ['only' => ['show']]);
    Route::post('aprobar-movimiento', [\App\Http\Controllers\MovimientoController::class, 'aprobar'])->name('aprobar-movimiento');
    Route::post('aprobar-prestamo', [\App\Http\Controllers\PrestamoController::class, 'aprobar'])->name('aprobar-prestamo');
    //Route::get('actualizacion-campos/puntos', [\App\Http\Controllers\ActualizacionCamposController::class, 'puntos']);
    //Route::get('actualizacion-campos/proveedores-movimientos', [\App\Http\Controllers\ActualizacionCamposController::class, 'proveedoresAMovimiento']);
    //Route::get('actualizacion-campos/pesos-ensayos', [\App\Http\Controllers\ActualizacionCamposController::class, 'pesosEnsayos']);

//    Route::get('actualizacion-campos/tara-animas', [\App\Http\Controllers\ActualizacionCamposController::class, 'taraAnimas']);
 //   Route::get('actualizacion-campos/cot-prom-ag-animas', [\App\Http\Controllers\ActualizacionCamposController::class, 'cotizacionPromedioAgAnimas']);
 //   Route::get('actualizacion-campos/cot-prom-ag-no-animas', [\App\Http\Controllers\ActualizacionCamposController::class, 'obsCotizacionPromedioAgNoAnimas']);

   //Route::get('actualizacion-campos/monto-final', [\App\Http\Controllers\ActualizacionCamposController::class, 'montoFinalVenta']);
   // Route::get('actualizacion-campos/detalle-activo', [\App\Http\Controllers\ActualizacionCamposController::class, 'detalleActivo']);
    //Route::get('actualizacion-campos/ensayo-origen', [\App\Http\Controllers\ActualizacionCamposController::class, 'origenEnsayo']);

    //Route::get('actualizacion-campos/montos-laboratorios', [\App\Http\Controllers\ActualizacionCamposController::class, 'montoLaboratorios']);

    Route::resource('bono', App\Http\Controllers\BonoController::class, ['only' => ['show']]);
    Route::post('aprobar-devolucion', [\App\Http\Controllers\BonoController::class, 'aprobar'])->name('aprobar-devolucion');
    Route::get('liquidacion-automatica', [\App\Http\Controllers\FormularioLiquidacionController::class, 'liquidacionAutomatica'])->name('liquidacion-automatica');
    Route::delete('rechazar-movimiento/{id}', [\App\Http\Controllers\MovimientoController::class, 'rechazar'])->name('rechazar-movimiento');
    Route::delete('rechazar-prestamo/{id}', [\App\Http\Controllers\PrestamoController::class, 'rechazar'])->name('rechazar-prestamo');
    Route::patch('aprobar-cliente/{id}', [\App\Http\Controllers\ClienteController::class, 'aprobar'])->name('aprobar-cliente');
    Route::get('venta-pendiente/{id}', [\App\Http\Controllers\VentaController::class, 'getVenta'])->name('venta-pendiente');
    Route::post('aprobar-venta', [\App\Http\Controllers\VentaController::class, 'aprobar'])->name('aprobar-venta');
    Route::post('actualizar-producto-lote', [\App\Http\Controllers\FormularioLiquidacionController::class, 'cambiarProducto'])->name('actualizar-producto-lote');
    Route::post('editar-valor-descuento', [\App\Http\Controllers\DescuentoController::class, 'editarValor'])->name('editar-valor-descuento');

    Route::get('cotizacion-oficial-detalle/{fecha}', [\App\Http\Controllers\CotizacionOficialController::class, 'getDetalle'])->name('cotizacion-oficial-detalle');
    Route::post('aprobar-cotizacion-oficial', [\App\Http\Controllers\CotizacionOficialController::class, 'aprobar'])->name('aprobar-cotizacion-oficial');
    Route::get('agregar-cuenta-antigua', [\App\Http\Controllers\CuentaCobrarController::class, 'agregarAntigua'])->name('agregar-cuenta-antigua');
    Route::post('store-cuenta-antigua', [\App\Http\Controllers\CuentaCobrarController::class, 'storeAntigua'])->name('store-cuenta-antigua');

    Route::post('aprobar-retencion-caja', [\App\Http\Controllers\RetencionPagoController::class, 'aprobarACaja'])->name('aprobar-retencion-caja');
    Route::get('clientes-registrados', [\App\Http\Controllers\ClienteController::class, 'getCLientesRegistrados'])->name('clientes-registrados');
    Route::get('clientes-editados', [\App\Http\Controllers\ClienteController::class, 'getClientesEditados'])->name('clientes-editados');
    Route::get('productores-finalizados', [\App\Http\Controllers\CooperativaController::class, 'getFinalizados'])->name('productores-finalizados');
    /*
        Route::get('actualizacion-campos/saldo-favor', [\App\Http\Controllers\ActualizacionCamposController::class, 'saldoFavor']);
        Route::get('actualizacion-campos/liquido-pagable', [\App\Http\Controllers\ActualizacionCamposController::class, 'liquidoPagable']);
        Route::get('actualizacion-campos/total-anticipo', [\App\Http\Controllers\ActualizacionCamposController::class, 'totalAnticipo']);
        Route::get('actualizacion-campos/total-bonificacion', [\App\Http\Controllers\ActualizacionCamposController::class, 'totalBonificacion']);
        Route::get('actualizacion-campos/total-retencion-descuento', [\App\Http\Controllers\ActualizacionCamposController::class, 'totalRetencionDescuento']);
        Route::get('actualizacion-campos/regalia-minera', [\App\Http\Controllers\ActualizacionCamposController::class, 'regaliaMinera']);
        Route::get('actualizacion-campos/formulario-descuento', [\App\Http\Controllers\ActualizacionCamposController::class, 'formularioDescuento']);
    */
    Route::get('actualizacion-campos/prueba-saldo-favor', [\App\Http\Controllers\ActualizacionCamposController::class, 'pruebaSaldoFavor']);
    Route::get('actualizacion-campos/prueba-liquido-pagable', [\App\Http\Controllers\ActualizacionCamposController::class, 'pruebaLiquidoPagable']);
    Route::get('actualizacion-campos/prueba-total-anticipo', [\App\Http\Controllers\ActualizacionCamposController::class, 'pruebaTotalAnticipo']);
    Route::get('actualizacion-campos/prueba-total-bonificacion', [\App\Http\Controllers\ActualizacionCamposController::class, 'pruebaTotalBonificacion']);
    Route::get('actualizacion-campos/prueba-total-retencion-descuento', [\App\Http\Controllers\ActualizacionCamposController::class, 'pruebaTotalRetencionDescuento']);
    Route::get('actualizacion-campos/prueba-regalia-minera', [\App\Http\Controllers\ActualizacionCamposController::class, 'pruebaRegaliaMinera']);
    Route::get('actualizacion-campos/prueba-cuenta-cobrar', [\App\Http\Controllers\ActualizacionCamposController::class, 'pruebaCuentaCobrar']);

    Route::get('actualizacion-campos/prueba-neto-venta', [\App\Http\Controllers\ActualizacionCamposController::class, 'pruebanetoVenta']);
    Route::get('actualizacion-campos/prueba-peso-seco', [\App\Http\Controllers\ActualizacionCamposController::class, 'pruebaPesoSeco']);
    Route::get('actualizacion-campos/prueba-humedad', [\App\Http\Controllers\ActualizacionCamposController::class, 'pruebaHumedad']);
    Route::get('actualizacion-campos/prueba-humedad-kg', [\App\Http\Controllers\ActualizacionCamposController::class, 'pruebaHumedadKg']);
    Route::get('actualizacion-campos/prueba-ley-sn', [\App\Http\Controllers\ActualizacionCamposController::class, 'pruebaLeySn']);

 //   Route::get('actualizacion-campos/ventas-seco-neto', [\App\Http\Controllers\ActualizacionCamposController::class, 'netoVentaPesoSecoVenta']);
//    Route::get('actualizacion-campos/neto-humedo-concentrado', [\App\Http\Controllers\ActualizacionCamposController::class, 'netoHumedoConcentrado']);
  //  Route::get('actualizacion-campos/merma-sacos', [\App\Http\Controllers\ActualizacionCamposController::class, 'mermaSacos']);
  //  Route::get('actualizacion-campos/tara', [\App\Http\Controllers\ActualizacionCamposController::class, 'taraPlata']);

    //Route::get('actualizar-pass-cliente', [\App\Http\Controllers\ActualizacionCamposController::class, 'actualizarPassCliente']);
   // Route::get('actualizacion-campos/numeros-en-pagos', [\App\Http\Controllers\ActualizacionCamposController::class, 'numerosEnPagos']);

//    Route::get('actualizar-saldo-bnb', [\App\Http\Controllers\MovimientoController::class, 'actualizarSaldoBnb']);


    //Route::get('actualizacion-campos/cuenta-cobrar-ids', [\App\Http\Controllers\ActualizacionCamposController::class, 'actualizarCuentaCobrar']);

    //Route::get('actualizacion-campos/ley-sn', [\App\Http\Controllers\ActualizacionCamposController::class, 'leySn']);

//    Route::get('actualizacion-campos/humedad', [\App\Http\Controllers\ActualizacionCamposController::class, 'humedad']);
//    Route::get('actualizacion-campos/humedad-dos', [\App\Http\Controllers\ActualizacionCamposController::class, 'humedadDos']);
    //Route::get('actualizacion-campos/humedad-kg', [\App\Http\Controllers\ActualizacionCamposController::class, 'humedadKg']);
//    Route::get('actualizacion-campos/humedad-kg-dos', [\App\Http\Controllers\ActualizacionCamposController::class, 'humedadKgDos']);
    //Route::get('actualizacion-campos/cuenta-cobrar', [\App\Http\Controllers\ActualizacionCamposController::class, 'totalCuentaCobrar']);
    //Route::get('actualizacion-campos/cuenta-cobrar-dos', [\App\Http\Controllers\ActualizacionCamposController::class, 'totalCuentaCobrarDos']);
//    Route::get('actualizacion-campos/documentos-compras-uno', [\App\Http\Controllers\ActualizacionCamposController::class, 'storeDocumentosComprasUno']);
//    Route::get('actualizacion-campos/documentos-compras-dos', [\App\Http\Controllers\ActualizacionCamposController::class, 'storeDocumentosComprasDos']);
//    Route::get('actualizacion-campos/documentos-compras-tres', [\App\Http\Controllers\ActualizacionCamposController::class, 'storeDocumentosComprasTres']);
//    Route::get('actualizacion-campos/documentos-compras-cuatro', [\App\Http\Controllers\ActualizacionCamposController::class, 'storeDocumentosComprasCuatro']);
//    Route::get('actualizacion-campos/documentos-compras-cinco', [\App\Http\Controllers\ActualizacionCamposController::class, 'storeDocumentosComprasCinco']);
//    Route::get('actualizacion-campos/despacho-venta', [\App\Http\Controllers\ActualizacionCamposController::class, 'despachadoVenta']);
//    Route::get('actualizacion-campos/es-retirado', [\App\Http\Controllers\ActualizacionCamposController::class, 'esRetirado']);

//    Route::get('actualizacion-campos/retirar', [\App\Http\Controllers\ActualizacionCamposController::class, 'retirarLote']);

 //   Route::get('actualizacion-campos/fecha-promedio/{id}', [\App\Http\Controllers\ActualizacionCamposController::class, 'fechaPromedioVenta']);
 //   Route::get('actualizacion-campos/venta-mineral', [\App\Http\Controllers\ActualizacionCamposController::class, 'ventaMineral']);
  //  Route::get('actualizacion-campos/ley-ag', [\App\Http\Controllers\ActualizacionCamposController::class, 'leyAg']);
    Route::get('actualizacion-campos/descuentos-acumulativos', [\App\Http\Controllers\ActualizacionCamposController::class, 'descuentosAcumulativos']);
    //Route::get('actualizacion-campos/clientes-anticipos', [\App\Http\Controllers\ActualizacionCamposController::class, 'clientesAnticipo']);

    //Route::get('actualizacion-campos/descuentos-acumulativos-cambios', [\App\Http\Controllers\ActualizacionCamposController::class, 'descuentosAcumulativosCambios']);
  //  Route::get('actualizacion-campos/descuentos-libertad', [\App\Http\Controllers\ActualizacionCamposController::class, 'descuentosLibertad']);

 //   Route::get('actualizacion-campos/tara-antigua', [\App\Http\Controllers\ActualizacionCamposController::class, 'antiguaTaraAnimas']);
 //   Route::get('actualizacion-campos/cotizacion-manual-animas', [\App\Http\Controllers\ActualizacionCamposController::class, 'cotizacionManualAnimas']);
});
Route::group(['middleware' => ['operaciones']], function () {
 //   Route::resource('formularioLiquidacions', App\Http\Controllers\FormularioLiquidacionController::class, ['only' => ['store','create']]);
    Route::get('concluir-molienda/{id}', [\App\Http\Controllers\FormularioLiquidacionController::class, 'concluirMolienda'])->name('concluir-molienda');
//    Route::resource('pesajes-ventas', App\Http\Controllers\PesajeVentaController::class, ['only' => ['store', 'destroy']]);
    Route::resource('ensayos', App\Http\Controllers\LaboratorioEnsayoController::class, ['only' => ['index']]);
    Route::get('informe-ensayo/{id}', [\App\Http\Controllers\LaboratorioController::class ,'imprimirInforme'])->name('informe-ensayo');
});

Route::group(['middleware' => ['comercialOperaciones']], function () {
    Route::resource('formularioLiquidacions', App\Http\Controllers\FormularioLiquidacionController::class, ['only' => ['edit', 'update', 'show','store','create']]);
    Route::get('historials', [\App\Http\Controllers\HistorialController::class, 'index']);
    Route::resource('ventas', App\Http\Controllers\VentaController::class, ['only' => ['edit']]);
    Route::get('documents/{id}', [\App\Http\Controllers\DocumentoController::class, 'show'])->name('documents.show');
    Route::patch('registrar_documento/{id}', [\App\Http\Controllers\DocumentoController::class, 'registrarDocumento'])->name('registrar_documento');

    Route::get('documentos-ventas/{id}', [\App\Http\Controllers\DocumentosVentaController::class, 'show'])->name('documentos-ventas.show');
    Route::get('historial_ventas', [\App\Http\Controllers\HistorialVentaController::class, 'index']);
    Route::resource('pesajes-ventas', App\Http\Controllers\PesajeVentaController::class, ['only' => ['index', 'store', 'destroy']]);
    Route::resource('choferes', App\Http\Controllers\ChoferController::class, ['except' => ['show', 'destroy']]);
    Route::resource('vehiculos', App\Http\Controllers\VehiculoController::class, ['except' => ['show', 'destroy']]);
});
Route::get('valor-plata/{ley}', [\App\Http\Controllers\ValorPorToneladaController::class, 'getPagablePlata']);

Route::get('valor-ton-plata/{id}', [\App\Http\Controllers\ValorPorToneladaController::class, 'getValorPorTonPlata']);


Route::group(['middleware' => ['invitado']], function () {
    Route::get('clientes/lista/{id}', 'App\Http\Controllers\ClienteController@lista')->name('clientes.lista');
    Route::get('clientes/register/{id}', 'App\Http\Controllers\ClienteController@register')->name('clientes.register');
    Route::resource('clientes', App\Http\Controllers\ClienteController::class, ['except' => ['show', 'create', 'index']]);

});

Route::group(['middleware' => ['comercial']], function () {
    Route::patch('anular-formulario/{id}', [\App\Http\Controllers\FormularioLiquidacionController::class, 'anular'])->name('anular-formulario');

//    Route::resource('formularioLiquidacions', App\Http\Controllers\FormularioLiquidacionController::class, ['only' => ['show']]);

    Route::resource('contratos', App\Http\Controllers\ContratoController::class, ['only' => ['index', 'show']]);
    Route::resource('tablaAcopiadoras', App\Http\Controllers\TablaAcopiadoraController::class, ['only' => ['index', 'show']]);

    Route::resource('cotizaciones-clientes', App\Http\Controllers\CotizacionClienteController::class, ['only' => ['index']]);
    Route::post('cotizaciones-clientes/valor-tonelada', [\App\Http\Controllers\CotizacionClienteController::class, 'getValorPorTonelada']);
    Route::post('cotizaciones-clientes/imprimir', [\App\Http\Controllers\CotizacionClienteController::class, 'imprimir'])->name('cotizaciones-clientes.imprimir');

    Route::resource('materials', App\Http\Controllers\MaterialController::class, ['only' => ['index']]);
    Route::resource('cotizacions', App\Http\Controllers\CotizacionDiariaController::class, ['except' => ['show', 'destroy']]);
    Route::get('cotizacions/lista/{id}', 'App\Http\Controllers\CotizacionDiariaController@lista')->name('cotizacions.lista');
    Route::get('cotizacions/register/{id}', 'App\Http\Controllers\CotizacionDiariaController@register')->name('cotizacions.register');
    Route::get('cotizacions/create-multiple', 'App\Http\Controllers\CotizacionDiariaController@createMultiple')->name('cotizacions.createMultiple');
    Route::post('cotizacions/storeMultiple', 'App\Http\Controllers\CotizacionDiariaController@storeMultiple')->name('cotizacions.storeMultiple');

    Route::resource('cotizacionOficials', App\Http\Controllers\CotizacionOficialController::class, ['except' => ['show', 'destroy']]);
    Route::get('cotizacionOficials/create-multiple', 'App\Http\Controllers\CotizacionOficialController@createMultiple')->name('cotizacionOficials.createMultiple');
    Route::post('cotizacionOficials/storeMultiple', 'App\Http\Controllers\CotizacionOficialController@storeMultiple')->name('cotizacionOficials.storeMultiple');

    Route::patch('documentos-cooperativas/registrar/{id}', [\App\Http\Controllers\CooperativaController::class, 'registrarDocumento'])->name('cooperativas.registrar-documento');



    Route::get('cambiar-estado-cliente/{id}/{estado}', [\App\Http\Controllers\ClienteController::class, 'cambiarEstado'])->name('clientes.cambiarEstado');

    Route::resource('lotes', App\Http\Controllers\LoteController::class, ['only' => ['index']]);

    Route::resource('ventas', App\Http\Controllers\VentaController::class, ['only' => ['store', 'destroy', 'update']]);
    Route::patch('documentos-ventas/registrar/{id}', [\App\Http\Controllers\DocumentosVentaController::class, 'registrar'])->name('documentos-ventas.registrar');
    Route::post('ventas/actualizar', [\App\Http\Controllers\VentaController::class, 'actualizar'])->name('ventas.actualizar');
    Route::post('enviar-operaciones', [\App\Http\Controllers\VentaController::class, 'enviarOperaciones'])->name('ventas.enviar-operaciones');

    Route::put('cambiar-tornaguia/{id}', [\App\Http\Controllers\FormularioLiquidacionController::class, 'cambiarTornaguia']);

    Route::put('ventas/cambiar-estado/{id}', [\App\Http\Controllers\VentaController::class, 'cambiarEstado'])->name('ventas.cambiar-estado');
    Route::put('ventas/finalizar/{id}', [\App\Http\Controllers\VentaController::class, 'finalizar'])->name('ventas.finalizar');

    Route::put('ventas/update-fields/{id}', [\App\Http\Controllers\VentaController::class, 'updateFields'])->name('ventas.update-fields');

    Route::get('descuentosBonificaciones/lista/{id}', 'App\Http\Controllers\DescuentoController@lista')->name('descuentosBonificaciones.lista');
    Route::get('descuentosBonificaciones/register/{id}', 'App\Http\Controllers\DescuentoController@register')->name('descuentosBonificaciones.register');
    Route::get('descuentosBonificaciones/{id}/{estado}/cambiarEstado', 'App\Http\Controllers\DescuentoController@cambiarEstado')->name('descuentosBonificaciones.cambiarEstado');
    Route::resource('descuentosBonificaciones', App\Http\Controllers\DescuentoController::class, ['except' => ['show', 'destroy']]);

    Route::post('agregar-descuento', [\App\Http\Controllers\ApiDescuentoBonificacionController::class, 'store'])->name('agregar-descuento');
    Route::delete('eliminar-descuento', [\App\Http\Controllers\ApiDescuentoBonificacionController::class, 'destroy'])->name('eliminar-descuento');

    Route::get('descuentos-by-formulario', [\App\Http\Controllers\ApiDescuentoBonificacionController::class, 'index'])->name('descuentos-by-formulario');
    Route::get('descuentos-faltantes', [\App\Http\Controllers\ApiDescuentoBonificacionController::class, 'descuentosFaltantes'])->name('descuentos-faltantes');

    Route::get('get-resumen/{id}', [App\Http\Controllers\FormularioLiquidacionController::class, 'getResumen']);


    Route::put('finalizar-formulario/{id}', [\App\Http\Controllers\FormularioLiquidacionController::class, 'finalizar']);

    Route::get('contarDirimicion/{id}', 'App\Http\Controllers\LaboratorioController@contarDirimicion');
    Route::get('laboratorios/eliminar/{origen}/{id}/', 'App\Http\Controllers\LaboratorioController@eliminar');
    Route::get('laboratorios/copiar/{id}/', 'App\Http\Controllers\LaboratorioController@copiarACliente');
    Route::get('laboratorioPrecios/{laboratorioId}/{producto}', [App\Http\Controllers\LaboratorioPrecioController::class, 'getPorLaboratorioProducto']);

    Route::get('laboratorios/actualizar/{id}/{valor}', 'App\Http\Controllers\LaboratorioController@actualizar');
    Route::get('laboratorios/actualizarUnidad/{formulario_id}/{mineral_id}/{unidad}', 'App\Http\Controllers\LaboratorioController@actualizarUnidad');
    Route::get('laboratorios/quitarDirimicion/{id}', 'App\Http\Controllers\LaboratorioController@quitarDirimicion');
    Route::get('get-laboratorios/{id}', 'App\Http\Controllers\LaboratorioController@getLaboratorios');

//    Route::resource('costos-ventas', App\Http\Controllers\CostoVentaController::class, ['only' => ['index', 'store', 'destroy']]);

    Route::resource('costos-ventas', App\Http\Controllers\CostoVentaController::class, ['only' => ['store', 'destroy']]);

    Route::resource('anticipos', App\Http\Controllers\AnticipoController::class);
    Route::resource('anticipos-ventas', App\Http\Controllers\AnticipoVentaController::class);

    Route::put('update_valor_por_tonelada/{id}', [\App\Http\Controllers\ValorPorToneladaController::class, 'updateValorPorTonelada'])->name('update_valor_por_tonelada');

    Route::put('restar-valor-por-tonelada/{id}', [\App\Http\Controllers\ValorPorToneladaController::class, 'restarValor'])->name('restar-valor-por-tonelada');

    Route::resource('concentrados', App\Http\Controllers\ConcentradoController::class, ['only' => ['store', 'destroy', 'update']]);
//    Route::resource('concentrados', App\Http\Controllers\ConcentradoController::class, ['only' => ['index', 'store', 'destroy', 'update']]);

    Route::get('costos/actualizar/{tipo}/{id}/{monto}', [App\Http\Controllers\CostoController::class, 'actualizarLaboratorio']);

    Route::post('concentrados-actualizar-merma', [\App\Http\Controllers\ConcentradoController::class, 'actualizarMerma'])->name('concentrados-actualizar-merma');

    Route::resource('bonos', App\Http\Controllers\BonoController::class, ['only' => ['index', 'store', 'destroy']]);
    Route::resource('cuentas-cobrar', App\Http\Controllers\CuentaCobrarController::class, ['only' => ['index']]);
    Route::post('transferir-cuenta', [\App\Http\Controllers\CuentaCobrarController::class, 'transferir'])->name('cuentas-cobrar.transferir');
    Route::get('cuentas-cobrar-cliente/{id}', [\App\Http\Controllers\CuentaCobrarController::class, 'getCuentasCobrarCliente'])->name('cuentas-cobrar-cliente');
    Route::post('agregar-cuenta', [\App\Http\Controllers\CuentaCobrarController::class, 'transferirDeClienteAFormulario'])->name('cuentas-cobrar.agregar');
    Route::post('agregar-cuenta-pendiente', [\App\Http\Controllers\CuentaCobrarController::class, 'transferirDePendienteAFormulario'])->name('cuentas-cobrar.agregar-pendiente');

    Route::post('dividir-prestamo', [\App\Http\Controllers\PrestamoController::class, 'dividir'])->name('prestamos.dividir');

    Route::delete('eliminar-documento-cooperativa/{id}', [\App\Http\Controllers\CooperativaController::class, 'eliminarDocumento'])->name('eliminar-documento-cooperativa');
    Route::get('get_municipios/{id}', 'App\Http\Controllers\MunicipioController@getMunicipios');
    Route::resource('descuentos-catalogos', App\Http\Controllers\DescuentoCatalogoController::class, ['only' => ['store']]);

    Route::delete('eliminar-documento-compra/{id}', [\App\Http\Controllers\DocumentoController::class, 'eliminarDocumento'])->name('eliminar-documento-compra');
    Route::resource('prestamos', App\Http\Controllers\PrestamoController::class, ['only' => ['store', 'create']]);
    Route::get('puntos-cliente/{id}', 'App\Http\Controllers\ClienteController@createPuntos')->name('puntos-cliente');
    Route::post('canjear-puntos', [\App\Http\Controllers\ClienteController::class, 'canjearPuntos'])->name('canjear-puntos');
    Route::get('finalizar-compra-afuera/{id}', 'App\Http\Controllers\FormularioLiquidacionController@finalizarAfuera')->name('finalizar-compra-afuera');
    Route::post('enviar-lote-ingenio', [\App\Http\Controllers\ConcentradoController::class, 'enviarLote'])->name('enviar-lote-ingenio');

    Route::put('reiniciar-descuentos/{id}', [\App\Http\Controllers\FormularioLiquidacionController::class, 'reiniciarDescuentos']);
    Route::get('imprimir-informe-laboratorio/{productorId}/{inicio}/{fin}/{mineralId}', [\App\Http\Controllers\CooperativaController::class ,'imprimirInformeLaboratorio'])->name('imprimir-informe-laboratorio');
    Route::patch('finalizar-cooperativa/{id}', [\App\Http\Controllers\CooperativaController::class, 'finalizar'])->name('finalizar-cooperativa');


});

Route::group(['middleware' => ['contabilidad']], function () {
    Route::get('lista-pagos', 'App\Http\Controllers\MovimientoController@listaPagos')->name('movimientos.lista-pagos');
    Route::post('anular-pago', [\App\Http\Controllers\MovimientoController::class, 'anular'])->name('movimientos.anular-pago');
    Route::get('retenciones/{productorId}', 'App\Http\Controllers\RetencionPagoController@getRetenciones')->name('retenciones.lista');
    Route::post('aprobar-retencion', [\App\Http\Controllers\RetencionPagoController::class, 'aprobar'])->name('aprobar-retencion');
    Route::get('devoluciones/{formId}', 'App\Http\Controllers\BonoController@getDevoluciones')->name('devoluciones-por-lote');
    Route::get('retenciones-detalle/{id}', 'App\Http\Controllers\RetencionPagoController@getDetalle')->name('retenciones.detalle');
    Route::get('mostrar-documento-cooperativa/{id}', 'App\Http\Controllers\CooperativaController@mostrarDocumento')->name('mostrar-documento-cooperativa');
    Route::post('verificar-despacho', [\App\Http\Controllers\VentaController::class, 'verificarDespacho']);


});
Route::group(['middleware' => ['contabilidadComercialInvitado']], function () {
    Route::resource('cooperativas', App\Http\Controllers\CooperativaController::class, ['except' => ['destroy']]);

});
Route::group(['middleware' => ['contabilidadComercialOperaciones']], function () {
    Route::resource('formularioLiquidacions', App\Http\Controllers\FormularioLiquidacionController::class, ['only' => ['index']]);
    Route::resource('ventas', App\Http\Controllers\VentaController::class, ['only' => ['index', 'show' ]]);
    Route::get('ventas/ordenVenta/{id}', 'App\Http\Controllers\PesajeVentaController@imprimirOrdenVenta')->name('ventas.ordenVenta');
    Route::get('ventas/ordenDespacho/{id}', 'App\Http\Controllers\VentaController@imprimirOrdenDespacho')->name('ventas.ordenDespacho');
    Route::get('mostrar-documento-venta/{id}', 'App\Http\Controllers\DocumentosVentaController@mostrar')->name('mostrar-documento-venta');
   // Route::resource('cooperativas', App\Http\Controllers\CooperativaController::class, ['only' => ['index']]);

    Route::get('kardex', 'App\Http\Controllers\FormularioLiquidacionController@kardex')->name('formularioLiquidacions.kardex');
    Route::get('cooperativas-retenciones', 'App\Http\Controllers\ApiDescuentoBonificacionController@getRetencionesCooperativa');
    Route::get('cooperativas/kardex/{id}', 'App\Http\Controllers\CooperativaController@kardex')->name('cooperativas.kardex');
    Route::get('cooperativas/reporte-rapido/{id}', 'App\Http\Controllers\CooperativaController@getReporteRapido')->name('cooperativas.reporte-rapido');
    Route::get('cooperativas/reporte-contabilidad/{id}', 'App\Http\Controllers\CooperativaController@getReporteContabilidad')->name('cooperativas.reporte-contabilidad');

    Route::get('cooperativas/resumen-contabilidad/{id}', 'App\Http\Controllers\CooperativaController@getResumenPdf')->name('cooperativas.resumen-contabilidad');

    Route::resource('tipoReportes', App\Http\Controllers\TipoReporteController::class, ['except' => ['show', 'destroy']]);
    Route::resource('campoReportes', App\Http\Controllers\CampoReporteController::class, ['only' => ['edit', 'update', 'index']]);
    Route::get('mostrar-documento/{id}', 'App\Http\Controllers\DocumentoController@mostrar')->name('mostrar-documento');
    Route::resource('concentrados', App\Http\Controllers\ConcentradoController::class, ['only' => ['index']]);
    Route::get('composito/{id}', 'App\Http\Controllers\VentaController@getComposito')->name('get-composito');
    Route::resource('costos-ventas', App\Http\Controllers\CostoVentaController::class, ['only' => ['index']]);
    Route::get('materiales-stock', 'App\Http\Controllers\ReporteController@getStock')->name('materiales-stock');

//    Route::resource('movimientos', App\Http\Controllers\MovimientoController::class, ['only' => ['create', 'store']]);
    Route::get('lab/informes-colquechaca', [\App\Http\Controllers\Lab\RecepcionController::class ,'getInformeColquechaca'])->name('get-informes-colquechaca-lab');

});
Route::group(['middleware' => ['todos']], function () {
    Route::resource('movimientos', App\Http\Controllers\MovimientoController::class, ['only' => ['create', 'store']]);
    Route::resource('proveedores', App\Http\Controllers\ProveedorController::class, ['except' => ['show', 'destroy']]);
    Route::get('get_proveedores', [\App\Http\Controllers\ProveedorController::class, 'getProveedores']);
    Route::get('get-catalogo-movimientos/{tipo}', [\App\Http\Controllers\MovimientoController::class, 'getCatalogo']);
    Route::get('get-lotes-movimientos/{tipo}', [\App\Http\Controllers\MovimientoController::class, 'getLotes']);
    Route::resource('activos-fijos', \App\Http\Controllers\Activo\ActivoFijoController::class, ['only' => ['index', 'edit']]);
    Route::post('impresion-multiple-activos', [\App\Http\Controllers\Activo\ActivoFijoController::class, 'imprimirMultiple'])->name('impresion-multiple-activos');
});
Route::group(['middleware' => ['activos']], function () {
    Route::resource('activos-fijos', \App\Http\Controllers\Activo\ActivoFijoController::class, ['only' => ['create', 'store', 'update']]);
    Route::post('/activos-fijos/actualizar-cantidad', [\App\Http\Controllers\Activo\ActivoFijoController::class, 'actualizarCantidad'])->name('activos-fijos.actualizarCantidad');
    Route::get('proximo-codigo-activo/{id}', [\App\Http\Controllers\Activo\ActivoFijoController::class, 'getProximoCodigo']);
    Route::get('baja-activo/{id}', [\App\Http\Controllers\Activo\ActivoFijoBajaController::class, 'nuevaBaja'])->name('baja-activo');
    Route::resource('bajas-activos', \App\Http\Controllers\Activo\ActivoFijoBajaController::class, ['only' => ['edit', 'show','store','destroy']]);
    Route::get('imprimir-activo-fijo/{id}', [\App\Http\Controllers\Activo\ActivoFijoController::class, 'imprimir'])->name('imprimir-activo-fijo');
    Route::get('imprimir-acta-activo/{id}/{inicio}/{fin}', [\App\Http\Controllers\Activo\ActivoFijoController::class, 'generarActa'])->name('imprimir-acta-activo');
    Route::resource('tipos-activos', \App\Http\Controllers\Activo\TipoController::class, ['only' =>['index','create','store','edit','update']]);
    Route::resource('facturas', \App\Http\Controllers\Activo\DetalleActivosController::class, ['only' => ['store','destroy']]);
    Route::get('nueva-factura/{id}', [\App\Http\Controllers\Activo\DetalleActivosController::class, 'nuevaFactura'])->name('nueva-factura');


});

Route::group(['middleware' => ['pesaje']], function () {

    Route::resource('compradores', App\Http\Controllers\CompradorController::class, ['except' => ['show', 'destroy']]);


    Route::get('get_compradores', [\App\Http\Controllers\CompradorController::class, 'getCompradores']);
});
Route::group(['middleware' => ['cajaContabilidad']], function () {
    Route::get('pagos/anticipos', 'App\Http\Controllers\MovimientoController@getAnticipos')->name('pagos.anticipos');
    Route::get('cobros/anticipos-ventas', 'App\Http\Controllers\AnticipoVentaController@getAnticiposCaja')->name('anticipos_ventas.pagos');

    Route::get('pagos-devoluciones', 'App\Http\Controllers\MovimientoController@getDevoluciones')->name('pagos.devoluciones');
    Route::resource('cajas', App\Http\Controllers\CajaController::class, ['only' => ['index']]);
    Route::resource('retenciones-pagos', App\Http\Controllers\RetencionPagoController::class, ['only' => ['index']]);
    Route::get('pagos-cuentas-cobrar', 'App\Http\Controllers\CuentaCobrarController@getCuentasClientes')->name('pagos.cuentas');
    Route::resource('movimientos', App\Http\Controllers\MovimientoController::class, ['only' => ['index']]);
    Route::get('reporte-pagos', 'App\Http\Controllers\MovimientoController@reporte')->name('movimientos.reporte');
    Route::get('cajas/comprobante', 'App\Http\Controllers\CajaController@comprobante')->name('cajas.comprobante');
    Route::get('cajas/imprimir-comprobante', 'App\Http\Controllers\CajaController@imprimirComprobante')->name('cajas.imprimir-comprobante');
    Route::get('cuentas-cobrar/{id}/imprimir', 'App\Http\Controllers\CuentaCobrarController@imprimir')->name('cuentas.reporte');

    Route::get('retenciones-pagos/{id}/imprimir', 'App\Http\Controllers\RetencionPagoController@imprimir')->name('retencion-pago.reporte');
    Route::get('movimientos/{id}/imprimir', 'App\Http\Controllers\MovimientoController@imprimir')->name('movimientos.recibo');
    Route::get('prestamos/{id}/imprimir', 'App\Http\Controllers\PrestamoController@imprimir')->name('prestamos.imprimir');
    Route::get('pagos-ventas', 'App\Http\Controllers\VentaController@getVentasCaja')->name('ventas.caja');
    Route::get('ventas/{id}/imprimir', 'App\Http\Controllers\VentaController@imprimir')->name('ventas.recibo');
    Route::resource('prestamos', App\Http\Controllers\PrestamoController::class, ['only' => ['index']]);
    Route::get('prestamos-emitidos', 'App\Http\Controllers\PrestamoController@getPrestamosEmitidos')->name('prestamos-emitidos');

    Route::get('retenciones-incluidas/{id}', 'App\Http\Controllers\RetencionPagoController@getRetencionesIncluidas')->name('retenciones-incluidas');

    Route::get('retenciones-detalle-pdf/{id}', 'App\Http\Controllers\RetencionPagoController@getDetallePdf')->name('retenciones-detalle-pdf');
    Route::get('pagos-dolares/{id}/imprimir', 'App\Http\Controllers\PagoDolarController@imprimir')->name('pagos-dolares.recibo');
    Route::resource('pagos-dolares', App\Http\Controllers\PagoDolarController::class, ['only' => ['index']]);

});

Route::group(['middleware' => ['caja']], function () {
    Route::resource('cajas', App\Http\Controllers\CajaController::class, ['only' => ['store']]);


    Route::post('registrar-pago-anticipo', [\App\Http\Controllers\MovimientoController::class, 'registrarAnticipo'])->name('movimientos.registrar-anticipo');
    Route::post('registrar-cobro-anticipo_venta', [\App\Http\Controllers\AnticipoVentaController::class, 'registrarCobro'])->name('anticipos_ventas.registrar-cobro');
    Route::post('registrar-pago-devolucion', [\App\Http\Controllers\MovimientoController::class, 'registrarDevolucion'])->name('movimientos.registrar-devolucion');
    Route::post('registrar-pago-cuenta', [\App\Http\Controllers\CuentaCobrarController::class, 'registrarCuenta'])->name('registrar-pago-cuenta');
    Route::post('registrar-factura', [\App\Http\Controllers\MovimientoController::class, 'registrarFactura'])->name('registrar-factura');
    Route::post('registrar-pago-retencion', [\App\Http\Controllers\RetencionPagoController::class, 'registrarPago'])->name('registrar-pago-retencion');
    Route::post('registrar-pago-venta', [\App\Http\Controllers\VentaController::class, 'registrarPago'])->name('ventas.registrar-pago');
    Route::post('registrar-transferencia', [\App\Http\Controllers\MovimientoController::class, 'storeTransferencia'])->name('registrar-transferencia');
    Route::post('registrar-pago-prestamo', [\App\Http\Controllers\PrestamoController::class, 'registrarPago'])->name('prestamos.registrar-pago');
    Route::post('registrar-pago-terceros', [\App\Http\Controllers\MovimientoController::class, 'registrarPagoTerceros'])->name('registrar-pago-terceros');
    Route::post('registrar-pago-laboratorio', [\App\Http\Controllers\PagoLaboratorioPesajeController::class, 'storeLaboratorio'])->name('registrar-pago-laboratorio');
    Route::post('registrar-pago-pesaje', [\App\Http\Controllers\PagoLaboratorioPesajeController::class, 'storePesaje'])->name('registrar-pago-pesaje');
    Route::get('get-precios-laboratorios/{id}', [\App\Http\Controllers\LaboratorioPrecioController::class, 'getPrecios']);
    Route::get('comprobante-laboratorio/{ids}', [\App\Http\Controllers\PagoLaboratorioPesajeController::class, 'imprimirLaboratorio']);
    Route::get('comprobante-pesaje/{ids}', [\App\Http\Controllers\PagoLaboratorioPesajeController::class, 'imprimirPesaje']);
    Route::resource('pagos-dolares', App\Http\Controllers\PagoDolarController::class, ['only' => ['create', 'store']]);

});

Route::group(['middleware' => ['cajaComercialContabilidad']], function () {
    Route::get('imprimirFormulario/{formulario_id}/{nombre?}', ['as' => 'imprimirFormulario', 'uses' => 'App\Http\Controllers\FormularioLiquidacionController@imprimir']);
    Route::get('anticipos-ventas/{id}/imprimir', 'App\Http\Controllers\AnticipoVentaController@imprimir')->name('imprimir_anticipo_venta');
    Route::get('anticipos/{id}/imprimir', 'App\Http\Controllers\AnticipoController@imprimir')->name('imprimir_anticipo');
    Route::get('bonos/{id}/imprimir', 'App\Http\Controllers\BonoController@imprimir')->name('imprimir_bono');
    Route::get('cuentas-cobrar-pendientes', 'App\Http\Controllers\CuentaCobrarController@getPendientesDePago')->name('cuentas-cobrar-pendientes');
    Route::get('cuentas-cobrar-total', 'App\Http\Controllers\CuentaCobrarController@getCuentasTotal')->name('cuentas-cobrar-total');
    Route::get('cuentas-cobrar-historial/{id}', 'App\Http\Controllers\CuentaCobrarController@getHistorial')->name('cuentas-cobrar-historial');

});
Route::get('get_choferes_android', [\App\Http\Controllers\ChoferController::class, 'getChoferesAndroid']);
Route::get('get_vehiculos_android', [\App\Http\Controllers\VehiculoController::class, 'getVehiculosAndroid']);
Route::get('get_clientes_android', [\App\Http\Controllers\ClienteController::class, 'getClientesAndroid']);
Route::get('formulario-pdf/{formulario_id}', ['as' => 'formulario-liquidacion-android', 'uses' => 'App\Http\Controllers\AndroidController@getFormularioPdf']);

Route::post('registrar-pesaje', [\App\Http\Controllers\AndroidController::class, 'registrar']);
Route::post('registrar-cliente', [\App\Http\Controllers\ClienteAndroidController::class, 'registrarCliente']);
Route::post('editarar-cliente', [\App\Http\Controllers\ClienteAndroidController::class, 'updateCliente']);
//Route::post('editar-android', [\App\Http\Controllers\AndroidController::class, 'editar']);
//Route::get('formularios-sin-peso', [\App\Http\Controllers\AndroidController::class, 'listaSinPeso']);
Route::get('formularios-a-editar', [\App\Http\Controllers\AndroidController::class, 'listaConError']);
Route::get('formularios-a-imprimir', [\App\Http\Controllers\AndroidController::class, 'listaAImprimir']);
Route::get('get-proximo-lote/{id}', [\App\Http\Controllers\AndroidController::class, 'getNumeroLote']);
Route::get('get-mis-pagos', [\App\Http\Controllers\ClienteAndroidController::class, 'listaPagos']);
Route::get('get-mis-compras', [\App\Http\Controllers\ClienteAndroidController::class, 'getCompras']);
Route::get('mis-laboratorios/{id}', [\App\Http\Controllers\ClienteAndroidController::class, 'getMisLaboratorios']);
Route::get('mis-laboratorios-empresa/{id}', [\App\Http\Controllers\ClienteAndroidController::class, 'getMisLaboratoriosEmpresa']);
Route::post('actualizar-laboratorio-cliente', [\App\Http\Controllers\ClienteAndroidController::class, 'updateLaboratorio']);
Route::get('get-clientes', [\App\Http\Controllers\ClienteAndroidController::class, 'getClientes']);
Route::get('get-personal', [\App\Http\Controllers\PersonalController::class, 'getPersonal']);
Route::get('get-productores', [\App\Http\Controllers\ClienteAndroidController::class, 'getCooperativas']);

Route::post('editar-pesaje', [\App\Http\Controllers\AndroidController::class, 'editarPesaje']);
Route::get('orden-despacho/{id}', [\App\Http\Controllers\AndroidController::class, 'getOrdenDespacho']);
Route::get('ventas-en-proceso', [\App\Http\Controllers\AndroidController::class, 'getVentas']);
Route::get('cantidad-ventas', [\App\Http\Controllers\AndroidController::class, 'getCantidadVentas']);
Route::get('get-retiros', [\App\Http\Controllers\AndroidController::class, 'getDevoluciones']);
Route::post('actualizar-despacho', [\App\Http\Controllers\AndroidController::class, 'actualizarDespacho']);
Route::post('actualizar-despacho-venta', [\App\Http\Controllers\AndroidController::class, 'actualizarDespachoVenta']);
Route::post('actualizar-retiro', [\App\Http\Controllers\AndroidController::class, 'actualizarRetiro']);
Route::post('autenticar-cliente', [\App\Http\Controllers\ClienteAndroidController::class, 'autenticar']);
Route::post('cambiar-pass-cliente', [\App\Http\Controllers\ClienteAndroidController::class, 'cambiarPassword']);



Route::get('get_choferes', [\App\Http\Controllers\ChoferController::class, 'getChoferes'])->name('autocomplete');
Route::get('get_vehiculos', [\App\Http\Controllers\VehiculoController::class, 'getVehiculos']);
Route::resource('tipos', App\Http\Controllers\TipoTablaController::class);

Route::get('get_clientes', [\App\Http\Controllers\ClienteController::class, 'getClientes']);
Route::get('get_by_cliente', [\App\Http\Controllers\CooperativaController::class, 'getByCliente']);

Route::get('edit-password', [\App\Http\Controllers\UserController::class, 'editPass'])->name('users.editPass');
Route::post('update-password', [\App\Http\Controllers\UserController::class, 'updatePass'])->name('users.updatePass');


//Route::get('valores/{id}', [App\Http\Controllers\ValorPorToneladaController::class, 'getValorPorTonZinc']);


// Route::resource('tablaAcopiadoraDetalles', App\Http\Controllers\TablaAcopiadoraDetalleController::class);

Route::get('reporte_pesaje/{id}', [\App\Http\Controllers\ReporteController::class, 'getPesaje'])->name('pesaje.reporte');
Route::get('descarga_pesaje/{id}', [\App\Http\Controllers\ReporteController::class, 'getPesajeDescarga'])->name('pesaje.descarga');
Route::get('cotizador/valor-tonelada', [\App\Http\Controllers\CotizadorAndroidController::class, 'getValorPorTonelada']);
Route::get('cotizador-proforma', [\App\Http\Controllers\CotizadorAndroidController::class, 'imprimir']);
Route::get('get-cotizaciones', [\App\Http\Controllers\CotizadorAndroidController::class, 'getCotizaciones']);

Route::get('saldo/{fecha}/{metodo}/{banco}', [\App\Http\Controllers\MovimientoController::class, 'getSaldo']);

Route::get('saldo-caja/{fecha}', [\App\Http\Controllers\MovimientoController::class, 'getSaldosCaja']);

Route::get('boleta_pesaje/{id}', [\App\Http\Controllers\ReporteController::class, 'imprimirBoletaPesaje']);
Route::get('contrato_compra/{id}', [\App\Http\Controllers\FormularioLiquidacionController::class, 'imprimirContrato'])->name('imprimirContrato');

Route::resource('satisfaccion-cliente', App\Http\Controllers\SatisfaccionClienteController::class, ['only' => ['index', 'store']]);



Route::get('anticipo-pdf/{id}', 'App\Http\Controllers\ClienteAndroidController@imprimirAnticipo');
Route::get('devolucion-pdf/{id}', 'App\Http\Controllers\ClienteAndroidController@imprimirDevolucion');
Route::get('contrato-pdf/{id}', 'App\Http\Controllers\ClienteAndroidController@imprimirContrato');
Route::get('prestamo-pdf/{id}', 'App\Http\Controllers\ClienteAndroidController@imprimirPrestamo');

Route::group(['middleware' => ['laboratorio']], function () {
    Route::get('buscar-cliente-lab', 'App\Http\Controllers\Lab\ClienteController@getCliente');
    Route::put('actualizar-lote-lab/{id}', [\App\Http\Controllers\Lab\EnsayoController::class, 'actualizarLote'])->name('actualizar-lote-lab');
    Route::resource('ensayos-lab', App\Http\Controllers\Lab\EnsayoController::class);
    Route::resource('clientes-lab', App\Http\Controllers\Lab\ClienteController::class);
    Route::resource('proveedores-lab', App\Http\Controllers\Lab\ProveedorController::class);
    Route::resource('cajas-lab', App\Http\Controllers\Lab\PagoMovimientoController::class);
    Route::get('get-clientes-lab', 'App\Http\Controllers\Lab\ClienteController@getClientes');
    Route::get('get-proveedores-lab', 'App\Http\Controllers\Lab\ProveedorController@getProveedores');
    Route::post('registrar-pago-lab', [App\Http\Controllers\Lab\PagoMovimientoController::class, 'registrarPago'])->name('registrar-pago-lab');
    Route::get('imprimir-comprobante-lab/{id}', 'App\Http\Controllers\Lab\PagoMovimientoController@imprimir')->name('imprimir-comprobante-lab');
    Route::put('finalizar-recepcion-lab/{id}', [\App\Http\Controllers\Lab\RecepcionController::class, 'finalizarRecepcion']);
    Route::put('anular-recepcion-lab/{id}', [\App\Http\Controllers\Lab\RecepcionController::class, 'anular'])->name('anular-recepcion-lab');
    Route::put('anular-ensayos-lab/{id}', [\App\Http\Controllers\Lab\RecepcionController::class, 'anularEnsayos'])->name('anular-ensayos-lab');
    Route::resource('recepcion-lab', App\Http\Controllers\Lab\RecepcionController::class, ['only' => ['index', 'edit', 'update']]);
    Route::get('lab/get-rechazados', [\App\Http\Controllers\Lab\ReporteController::class ,'getRechazados'])->name('get-rechazados-lab');
    Route::get('lab/get-rechazados-pdf/{inicio}/{fin}', [\App\Http\Controllers\Lab\ReporteController::class ,'getRechazadosPdf'])->name('get-rechazados-pdf-lab');
    Route::get('lab/get-aceptados', [\App\Http\Controllers\Lab\ReporteController::class ,'getAceptados'])->name('get-aceptados-lab');
    Route::get('lab/get-aceptados-pdf/{inicio}/{fin}', [\App\Http\Controllers\Lab\ReporteController::class ,'getAceptadosPdf'])->name('get-aceptados-pdf-lab');
    Route::get('lab/get-finalizados', [\App\Http\Controllers\Lab\ReporteController::class ,'getFinalizados'])->name('get-finalizados-lab');
    Route::get('lab/get-finalizados-pdf/{inicio}/{fin}/{elemento}/{cliente}', [\App\Http\Controllers\Lab\ReporteController::class ,'getFinalizadosPdf'])->name('get-finalizados-pdf-lab');
    Route::get('lab/get-ingresos', [\App\Http\Controllers\Lab\ReporteController::class ,'getIngresos'])->name('get-ingresos-lab');
    Route::get('lab/get-ingresos-pdf/{inicio}/{fin}', [\App\Http\Controllers\Lab\ReporteController::class ,'getIngresosPdf'])->name('get-ingresos-pdf-lab');
    Route::get('lab/get-egresos', [\App\Http\Controllers\Lab\ReporteController::class ,'getEgresos'])->name('get-egresos-lab');
    Route::get('lab/get-egresos-pdf/{inicio}/{fin}', [\App\Http\Controllers\Lab\ReporteController::class ,'getEgresosPdf'])->name('get-egresos-pdf-lab');
    Route::get('lab/get-cajas', [\App\Http\Controllers\Lab\ReporteController::class ,'getCajas'])->name('get-cajas-lab');
    Route::get('lab/get-cajas-pdf/{inicio}/{fin}', [\App\Http\Controllers\Lab\ReporteController::class ,'getCajasPdf'])->name('get-cajas-pdf-lab');
    Route::get('lab/get-calibraciones-pdf/{tipo}/{inicio}/{fin}', [\App\Http\Controllers\Lab\ReporteController::class ,'getCalibracionesPdf'])->name('get-calibraciones-pdf-lab');
    Route::get('lab/get-temperaturas-pdf/{ambiente}/{inicio}/{fin}', [\App\Http\Controllers\Lab\ReporteController::class ,'getTemperaturasPdf'])->name('get-temperaturas-pdf-lab');
    Route::get('lab/get-accidentes-pdf/{inicio}/{fin}', [\App\Http\Controllers\Lab\ReporteController::class ,'getAccidentesPdf'])->name('get-accidentes-pdf-lab');
    Route::get('lab/get-tecnico', [\App\Http\Controllers\Lab\ReporteController::class ,'getReporteTecnico'])->name('get-tecnico-lab');


    Route::post('lab/enviar-caja', [App\Http\Controllers\Lab\RecepcionController::class, 'enviarCaja'])->name('enviar-caja-lab');
    Route::post('lab/finalizar-ensayo', [\App\Http\Controllers\Lab\RecepcionController::class, 'finalizarEnsayos'])->name('finalizar-ensayos-lab');
    Route::post('lab/anular-pago', [\App\Http\Controllers\Lab\PagoMovimientoController::class, 'anular'])->name('anular-pago-lab');
    Route::resource('lab/factores-volumetricos', App\Http\Controllers\Lab\FactorVolumetricoController::class, ['only' => ['index', 'store']]);
    Route::post('lab/actualizar-factor-volumetrico', [\App\Http\Controllers\Lab\FactorVolumetricoController::class, 'actualizar'])->name('actualizar-factor-volumetrico');

    Route::resource('lab/insumos', App\Http\Controllers\Lab\InsumoController::class, ['only' => ['index', 'store', 'show', 'destroy']]);
    Route::post('lab/actualizar-insumo', [\App\Http\Controllers\Lab\InsumoController::class, 'actualizar'])->name('actualizar-insumo');
    Route::post('lab/actualizar-inventario', [\App\Http\Controllers\Lab\InsumoController::class, 'actualizarInventario'])->name('actualizar-inventario-lab');

    Route::get('lab/get-insumos-pdf', [\App\Http\Controllers\Lab\InsumoController::class ,'getInsumosPdf'])->name('get-insumos-pdf-lab');

    Route::resource('lab/constantes-medidas', App\Http\Controllers\Lab\ConstanteMedidaController::class, ['only' => ['index', 'store']]);
    Route::post('lab/actualizar-constante-medida', [\App\Http\Controllers\Lab\ConstanteMedidaController::class, 'actualizar'])->name('actualizar-constante-medida');
    Route::resource('lab/rangos-mediciones', App\Http\Controllers\Lab\RangoMedicionController::class, ['only' => ['index', 'store']]);
    Route::post('lab/actualizar-rango-medicion', [\App\Http\Controllers\Lab\RangoMedicionController::class, 'actualizar'])->name('actualizar-rango-medicion');
    Route::resource('lab/calibraciones-balanzas', App\Http\Controllers\Lab\CalibracionBalanzaController::class, ['only' => ['index', 'store']]);
    Route::post('lab/actualizar-calibracion-balanza', [\App\Http\Controllers\Lab\CalibracionBalanzaController::class, 'actualizar'])->name('actualizar-calibracion-balanza');

    Route::resource('lab/temperaturas-humedades', App\Http\Controllers\Lab\TemperaturaHumedadController::class, ['only' => ['index', 'store']]);
    Route::post('lab/actualizar-temperatura-humedad', [\App\Http\Controllers\Lab\TemperaturaHumedadController::class, 'actualizar'])->name('actualizar-temperatura-humedad');

    Route::resource('lab/accidentes', App\Http\Controllers\Lab\AccidenteController::class, ['only' => ['index', 'store']]);
    Route::post('lab/actualizar-accidente', [\App\Http\Controllers\Lab\AccidenteController::class, 'actualizar'])->name('actualizar-accidente');

    Route::get('lab/registrar-sin-accidentes', [\App\Http\Controllers\Lab\AccidenteController::class ,'registrarSinAccidentes']);
});
Route::group(['middleware' => ['clienteLab']], function () {
    Route::resource('recepcion-lab', App\Http\Controllers\Lab\RecepcionController::class, ['only' => ['create', 'store', 'show']]);
    Route::get('inicio-lab', 'App\Http\Controllers\Lab\RecepcionController@inicio')->name('inicio-lab');

});

Route::get('ensayos-campos', [App\Http\Controllers\Lab\EnsayoController::class, 'ensayoLab'])->name('ensayos-campos');




Route::group(['middleware' => ['rrhh']], function () {

    Route::resource('asistencias',App\Http\Controllers\rrhh\AsistenciaController::class,['only'=>['index']]);
    Route::post('asistencias/edit', [\App\Http\Controllers\rrhh\AsistenciaController::class, 'updateAsistencia'])->name('asistencias.editar');
    Route::post('asistencias/importar', 'App\Http\Controllers\rrhh\AsistenciaController@importar')->name('asistencias.importar');
    //horas extra
    Route::resource('horas-extras',\App\Http\Controllers\rrhh\HoraExtraController::class);

    Route::patch('horas-extras/aprobado/{id}',[\App\Http\Controllers\rrhh\HoraExtraController::class,'aprobacionHoraExtra'])->name('hora-extra.aprobado');

    //Crear asistencia manualmente
    Route::get('crear-asistencias',[App\Http\Controllers\rrhh\AsistenciaController::class,'mostrarCrearAsistencia'])->name(('crear.asistencia'));
    Route::post('crear-asistencias/create',[App\Http\Controllers\rrhh\AsistenciaController::class,'crearAsistenciaManual'])->name('crear.asistencias-manual');
    Route::get('aprobar-asistencia/{id}',[App\Http\Controllers\rrhh\AsistenciaController::class,'mostrarAporbarAsistencia'])->name('aprobar.asistencia');
    Route::delete('rechazar-asistencia/{id}',[App\Http\Controllers\rrhh\AsistenciaController::class,'rechazarAsitencia'])->name('rechazar.asistencia');
    Route::patch('conceder-asistencia/{id}',[\App\Http\Controllers\rrhh\AsistenciaController::class,'aprobacionasistencia'])->name('conceder.asistencia');

    //calendario
    Route::get('calendario',[App\Http\Controllers\rrhh\AsistenciaController::class,'calendario'])->name(('calendario.index'));
    Route::post('calendario/create',[App\Http\Controllers\rrhh\AsistenciaController::class,'calendarioCrear'])->name(('calendario.crear'));
    //Tipo horario
    Route::get('tipos-horarios',[App\Http\Controllers\rrhh\AsistenciaController::class, 'tipoHorario'])->name('tipoHorario');
    Route::post('tipos-horarios/create',[App\Http\Controllers\rrhh\AsistenciaController::class, 'createTipoHorario'])->name('tipo-horario.create');
    Route::post('/tipo-horario/actualizar',[App\Http\Controllers\rrhh\AsistenciaController::class, 'editTipoHorario'])->name('tipo-horario.actualizar');
    Route::get('/tipos-horarios-personal/{id}',[App\Http\Controllers\rrhh\AsistenciaController::class, 'tipoHorarioPersonal'])->name('tipo-horario-personal');
    Route::post('tipos-horarios-personal/edit', [\App\Http\Controllers\rrhh\AsistenciaController::class, 'editarHorarioPersonal'])->name('tipo-horario-personal.editar');
    Route::post('/tipos-horarios-personal',[App\Http\Controllers\rrhh\AsistenciaController::class,'crearHorarioPersonal'])->name('tipo-horario-personal.crear');
    Route::delete('/tipo-horario-personal/eliminar/{id}',[App\Http\Controllers\rrhh\AsistenciaController::class,'eliminarHorarioPersonal'])->name('tipo-horario-personal.eliminar');
    //Permisos
    Route::get('permisos-general-pesonal',[\App\Http\Controllers\rrhh\PermisoController::class,'permisosAsignadoPersonal'])->name('mostrarpermisos.general');
    Route::get('tipos-permisos/create',[\App\Http\Controllers\rrhh\PermisoController::class,'motrarCrearPermisos'])->name('tipospermisos.create');
    Route::post('tipos-permisos/create/personal',[\App\Http\Controllers\rrhh\PermisoController::class,'crearTipoPermisosPersonal'])->name('tipos-permisos-personal.create');
    Route::post('tipos-permisos/create/permisos',[\App\Http\Controllers\rrhh\PermisoController::class,'crearTiposPermisos'])->name('tipos-permisos.create');



   //Feriado
    Route::get('feriados', 'App\Http\Controllers\rrhh\AsistenciaController@feriados')->name('feriados');
    Route::get('feriados/create', [App\Http\Controllers\rrhh\AsistenciaController::class, 'createFeriados'])->name('feriados.create');
    Route::post('feriados/store', [App\Http\Controllers\rrhh\AsistenciaController::class, 'storeFeriados'])->name('feriados.store');
    Route::delete('feriado/delete/{id}', [App\Http\Controllers\rrhh\AsistenciaController::class, 'deleteFeriados'])->name('feriado.delete');
    Route::resource('movimientos-catalogos', \App\Http\Controllers\MovimientoCatalagoController::class);
    Route::post('generar-factura', [App\Http\Controllers\VentaController::class, 'generarFactura'])->name('generar-factura');



});

Route::group(['middleware' => 'auth'], function () {
    //Asistencia
    Route::get('mis-asistencias',[App\Http\Controllers\rrhh\AsistenciaController::class,'mostrarAsistencia'])->name(('mis-asistencias'));
    Route::get('mis-horas-extra',[\App\Http\Controllers\rrhh\HoraExtraController::class,'miHoraExtra'])->name(('mis-horas-extra'));
    Route::get('solicitud-horas-extra',[\App\Http\Controllers\rrhh\HoraExtraController::class,'miHoraExtraSolicitud'])->name(('mis-horas-extra-solicitud'));

    //Permisos
                                    {{}}
    Route::get('mis-permisos',[\App\Http\Controllers\rrhh\PermisoController::class,'mostrarPermisos'])->name(('mis-permisos'));
    Route::resource('permisos', App\Http\Controllers\rrhh\PermisoController::class,['only'=>['create','store']]);
    Route::get('permisos/aprobacion/{id}',[\App\Http\Controllers\rrhh\PermisoController::class,'permisoUsuario'])->name('permiso.aprobacion');
    Route::delete('permisos/rechazados/{id}',[\App\Http\Controllers\rrhh\PermisoController::class,'rechazarPermiso'])->name('permisos.rechazados');
    Route::patch('permisos/aprobacion/exitosa/{id}',[\App\Http\Controllers\rrhh\PermisoController::class,'aprobacionPermiso'])->name('permiso.aprobado');
});
//planilla
Route::resource('planillas-sueldos', \App\Http\Controllers\rrhh\PlanillaController::class);


//Anticipos
Route::resource('anticipos-sueldos',App\Http\Controllers\rrhh\AnticipoSueldoController::class);


Route::get('informe-animas/{lote1}/{lote2}', [\App\Http\Controllers\LaboratorioController::class ,'imprimirInformeAnimas'])->name('informe-ensayo-animas');


Route::get('get-venta/{id}', [\App\Http\Controllers\VentaController::class, 'getVentaFactura']);


Route::get('imprimir-informe-ensayo/{id}', [\App\Http\Controllers\Lab\EnsayoController::class ,'imprimirInforme'])->name('imprimir-informe-ensayo');;


Route::get('escala-precios-estanio', [\App\Http\Controllers\CotizadorAndroidController::class, 'getTablaEstanio']);
Route::get('escala-precios-plomo-plata', [\App\Http\Controllers\CotizadorAndroidController::class, 'getTablaPlomoPlata']);
Route::get('escala-precios-zinc-plata', [\App\Http\Controllers\CotizadorAndroidController::class, 'getTablaZincPlata']);

Route::get('test', function () {
    return substr('abcdef', 4);

    $anio = date('y');
    $mes = date('m');

    $recepcion = Recepcion::where('mes', $mes)->where('anio', $anio)->max('numero');
    return ['numero' => $recepcion + 1, 'anio' => $anio];
    return date('w');
    $data = "Liquidación para Lote CM390B/23, en transferencia bancaria con recibo cheque 8535947";
    $whatIWant = substr($data, strpos($data, "bancaria con recibo") + 20);
    if(str_contains($whatIWant, "cheque"))
        $whatIWant = substr($whatIWant, strpos($whatIWant, "cheque") + 7);
    return $whatIWant;

    $venta= \App\Models\Venta::find(184);
    $date1 = new DateTime($venta->fecha_promedio);
    $date2 = new DateTime($venta->fecha_venta);
    $diff = $date1->diff($date2);
// will output 2 days
    echo $diff->days . ' days ';

    $devoluciones = PagoMovimiento::
    join('bono', 'bono.id', '=', 'pago_movimiento.origen_id')
        ->join('formulario_liquidacion', 'formulario_liquidacion.id', '=', 'bono.formulario_liquidacion_id')
        ->where('bono.es_cancelado', true)
        ->where('origen_type', Bono::class)
        ->orderByDesc('pago_movimiento.id')
        ->count();
//    $formularioLiquidacions = FormularioLiquidacion::
//        where(function ($q) {
//            $q
//                ->where('letra', "E")
//                ->whereIn('estado', [Estado::Liquidado, Estado::Composito, Estado::Vendido])
//                ->WhereHas('cliente', function ($q) {
//                    $q->where('cooperativa_id', "44")
//;
//                });
//        })
//        ->orderByDesc('id')->orderByDesc('numero_lote')
//        ->get();
//    $array = [];
//    foreach ($formularioLiquidacions as $f){
//        if($f->ley_ag < 20 and $f->ley_ag>0.00){
//            array_push($array, $f->id);
//        }
//    }
//    return $array;
//    $myString = 'CUENTAS POR COBRAR A PRUEBA DANIEL POR SALDO NEGATIVO NRO LOTE: CM1741D/22';
//    $words = explode(' ', $myString);
//    $lastWord = array_pop($words);
//    $numero = substr($lastWord, 0, -2);
//    $numero = (int) filter_var($numero, FILTER_SANITIZE_NUMBER_INT);
//    $anio = substr($lastWord, -2);
//    $letra = substr($lastWord, -4, 1);
//    return $numero;
//    return date("Y-m-d");
//    $f = FormularioLiquidacion::find(41);
//    return $f;
//    $cuenta=\App\Models\CuentaCobrar::find(7);
//    return $cuenta->prestamo_id;
});
