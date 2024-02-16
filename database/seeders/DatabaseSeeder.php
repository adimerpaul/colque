<?php

namespace Database\Seeders;

use App\Models\CampoReporte;
use App\Models\Chofer;
use App\Models\Cliente;
use App\Models\Comprador;
use App\Models\Contrato;
use App\Models\Cooperativa;
use App\Models\Costo;
use App\Models\CotizacionDiaria;
use App\Models\CotizacionOficial;
use App\Models\DescuentoBonificacion;
use App\Models\Empresa;
use App\Models\FormularioLiquidacion;
use App\Models\LaboratorioPrecio;
use App\Models\LaboratorioQuimico;
use App\Models\Producto;
use App\Models\ProductoMineral;
use App\Models\TipoCambio;
use App\Models\TipoReporte;
use App\Models\TipoTabla;
use App\Models\User;
use App\Models\Vehiculo;
use App\Patrones\Rol;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $faker = Factory::create();

        // \App\Models\User::factory(10)->create();
        $empresa = Empresa::create([
            'identificacion_tributaria' => '370883022',
            'razon_social' => 'Colquechaca Mining.',
            'direccion' => 'Av. Barzola entre Corihuayra, Celestino Gutierrez y Heroes de la Coronilla #174',
            'email' => 'info@colquechaca.com',
            'telefono' => '67200160',
            'celular' => '67200160',
            'alta' => true,
            'logo' => 'logo.png',
            'membrete' => '1618511742.png',

            'cantidad_usuario' => 100,

        ]);

        $personal = $empresa->personals()->create([
            'ci' => '3095304',
            'ci_add' => null,
            'expedido' => 'OR',
            'nombre_completo' => 'Saul Mamani M.',
            'celular' => '76137269',
            'empresa_id' => 1
        ]);

        $personal->user()->create([
            'email' => 'superadmin@colquechaca.com',
            'password' => Hash::make('123456'),
            'rol' => Rol::SuperAdmin,
            'alta' => true,
            'ultimo_cambio_password' => null,
            'personal_id' => 1,
        ]);

        \DB::table('mineral')->insert([
            ['simbolo' => 'Ag', 'nombre' => 'Plata', 'unidad_laboratorio' => 'D.M', 'margen_error'=> rand(3,5)],
            ['simbolo' => 'Pb', 'nombre' => 'Plomo', 'unidad_laboratorio' => '%', 'margen_error'=> rand(3,5)],
            ['simbolo' => 'Zn', 'nombre' => 'Zinc', 'unidad_laboratorio' => '%', 'margen_error'=> rand(3,5)],
            ['simbolo' => 'Sn', 'nombre' => 'Estaño', 'unidad_laboratorio' => '%', 'margen_error'=> rand(3,5)],
            ['simbolo' => 'Cu', 'nombre' => 'Cobre', 'unidad_laboratorio' => '%', 'margen_error'=> rand(3,5)],
            ['simbolo' => 'Sb', 'nombre' => 'Antimonio', 'unidad_laboratorio' => '%', 'margen_error'=> rand(3,5)],
        ]);

        \DB::table('producto')->insert([
            ['letra' => 'A', 'nombre' => 'Zinc Plata', 'costo_tratamiento' => 40, 'costo_pesaje' => 10, 'costo_comision' => 5],
            ['letra' => 'B', 'nombre' => 'Plomo Plata', 'costo_tratamiento' => 40, 'costo_pesaje' => 10, 'costo_comision' => 5],
            ['letra' => 'C', 'nombre' => 'Complejo', 'costo_tratamiento' => 40, 'costo_pesaje' => 10, 'costo_comision' => 5],
            ['letra' => 'D', 'nombre' => 'Estaño', 'costo_tratamiento' => 40, 'costo_pesaje' => 10, 'costo_comision' => 5],
        ]);


        ProductoMineral::create(['es_penalizacion' => false, 'producto_id' => 1, 'mineral_id' => 1]);
        ProductoMineral::create(['es_penalizacion' => false, 'producto_id' => 1, 'mineral_id' => 3]);
        ProductoMineral::create(['es_penalizacion' => true, 'producto_id' => 1, 'mineral_id' => 4]);
        ProductoMineral::create(['es_penalizacion' => false, 'producto_id' => 2, 'mineral_id' => 1]);
        ProductoMineral::create(['es_penalizacion' => false, 'producto_id' => 2, 'mineral_id' => 2]);
        ProductoMineral::create(['es_penalizacion' => true, 'producto_id' => 2, 'mineral_id' => 3]);
        ProductoMineral::create(['es_penalizacion' => false, 'producto_id' => 4, 'mineral_id' => 4]);


        $productos = Producto::where('id', '=', '3')->get();
        foreach ($productos as $producto) {
            $producto->productoMinerals()->createMany([
                ['es_penalizacion' => false, 'mineral_id' => 1],
                ['es_penalizacion' => false, 'mineral_id' => 2],
                ['es_penalizacion' => false, 'mineral_id' => 3],
            ]);
        }


        $fechaInicialCotizacion = '2020-10-01';

        for ($i = 1; $i <= 730; $i++) {
            TipoCambio::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'dolar_compra' => 6.86,
                'dolar_venta' => 6.96
            ]);
        }

        for ($i = 1; $i <= 730; $i++) {
            CotizacionDiaria::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'monto' => $faker->randomFloat(2, 22.7, 28.7),
                'unidad' => 'OT',
                'mineral_id' => 1
            ]);
            CotizacionDiaria::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'monto' => $faker->randomFloat(2, 0.8945, 0.8945),
                'unidad' => 'LF',
                'mineral_id' => 2
            ]);
            CotizacionDiaria::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'monto' => $faker->randomFloat(2, 1.2354, 1.2354),
                'unidad' => 'LF',
                'mineral_id' => 3
            ]);
            CotizacionDiaria::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'monto' => $faker->randomFloat(2, 1.2354, 1.2354),
                'unidad' => 'LF',
                'mineral_id' => 4
            ]);
            CotizacionDiaria::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'monto' => $faker->randomFloat(2, 1.2354, 1.2354),
                'unidad' => 'LF',
                'mineral_id' => 5
            ]);
            CotizacionDiaria::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'monto' => $faker->randomFloat(2, 1.2354, 1.2354),
                'unidad' => 'LF',
                'mineral_id' => 6
            ]);
        }

        for ($i = 1; $i <= 730; $i += 15) {
            CotizacionOficial::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'monto' => $faker->randomFloat(2, 22.7, 28.7),
                'unidad' => 'OT',
                'alicuota_exportacion' => $faker->randomFloat(2, 10.7, 30.7),
                'alicuota_interna' => $faker->randomFloat(2, 10.7, 30.7),
                'mineral_id' => 1
            ]);
            CotizacionOficial::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'monto' => $faker->randomFloat(2, 0.8945, 0.8945),
                'unidad' => 'LF',
                'alicuota_exportacion' => $faker->randomFloat(2, 10.7, 30.7),
                'alicuota_interna' => $faker->randomFloat(2, 10.7, 30.7),
                'mineral_id' => 2
            ]);
            CotizacionOficial::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'monto' => $faker->randomFloat(2, 1.2354, 1.2354),
                'unidad' => 'LF',
                'alicuota_exportacion' => $faker->randomFloat(2, 10.7, 30.7),
                'alicuota_interna' => $faker->randomFloat(2, 10.7, 30.7),
                'mineral_id' => 3
            ]);
            CotizacionOficial::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'monto' => $faker->randomFloat(2, 1.2354, 1.2354),
                'unidad' => 'LF',
                'alicuota_exportacion' => $faker->randomFloat(2, 10.7, 30.7),
                'alicuota_interna' => $faker->randomFloat(2, 10.7, 30.7),
                'mineral_id' => 4
            ]);
            CotizacionOficial::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'monto' => $faker->randomFloat(2, 1.2354, 1.2354),
                'unidad' => 'LF',
                'alicuota_exportacion' => $faker->randomFloat(2, 10.7, 30.7),
                'alicuota_interna' => $faker->randomFloat(2, 10.7, 30.7),
                'mineral_id' => 5
            ]);
            CotizacionOficial::create([
                'fecha' => date('Y-m-d', strtotime(date($fechaInicialCotizacion) . ' + ' . $i . ' days')),
                'monto' => $faker->randomFloat(2, 1.2354, 1.2354),
                'unidad' => 'LF',
                'alicuota_exportacion' => $faker->randomFloat(2, 10.7, 30.7),
                'alicuota_interna' => $faker->randomFloat(2, 10.7, 30.7),
                'mineral_id' => 6
            ]);
        }

        Chofer::factory(50)->create();
        Vehiculo::factory(30)->create();

        Cooperativa::factory(35)->hasClientes(5)->create();
        Comprador::factory(30)->create();

        $cooperativas = Cooperativa::get();

        foreach ($cooperativas as $cooperativa) {
            $cooperativa->descuentoBonificaciones()->create([
                'nombre' => 'FENCOMIN', 'valor' => '0.40', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Descuento'
            ]);

            $cooperativa->descuentoBonificaciones()->create([
                'nombre' => 'ADMINISTRACIÓN', 'valor' => '3.00', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Descuento'
            ]);
            $cooperativa->descuentoBonificaciones()->create([
                'nombre' => 'FENCOMIN-NORPO', 'valor' => '1.00', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Descuento'
            ]);

            $cooperativa->descuentoBonificaciones()->create([
                'nombre' => 'SISTEMA INTEGRAL DE PENSIONES', 'valor' => '1.00', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Descuento'
            ]);

            $cooperativa->descuentoBonificaciones()->create([
                'nombre' => 'COMIBOL', 'valor' => '1.00', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Retencion'
            ]);
            $cooperativa->descuentoBonificaciones()->create([
                'nombre' => 'CAJA NACIONAL DE SALUD', 'valor' => '1.80', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::ValorNetoVenta, 'tipo' => 'Retencion'
            ]);
            $cooperativa->descuentoBonificaciones()->create([
                'nombre' => 'BONO TRANSPORTE', 'valor' => '100', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Constante, 'en_funcion' => \App\Patrones\EnFuncion::Total, 'tipo' => 'Bonificacion'
            ]);
            $cooperativa->descuentoBonificaciones()->create([
                'nombre' => 'BONO LEY', 'valor' => '0.5', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Porcentaje, 'en_funcion' => \App\Patrones\EnFuncion::Total, 'tipo' => 'Bonificacion'
            ]);
            $cooperativa->descuentoBonificaciones()->create([
                'nombre' => 'BONO VIÁTICOS', 'valor' => '0.1', 'unidad' => \App\Patrones\UnidadDescuentoBonificacion::Constante, 'en_funcion' => \App\Patrones\EnFuncion::Total, 'tipo' => 'Bonificacion', 'alta' => false
            ]);
        };

        FormularioLiquidacion::factory(30)->create();
        $formularios = FormularioLiquidacion::get();

        foreach ($formularios as $f) {
            $descuentos = DescuentoBonificacion::whereCooperativaId($f->cliente->cooperativa_id)->whereAlta(true)->get();
            foreach ($descuentos as $row) {
                \DB::table('formulario_descuento')->insert([
                    'formulario_liquidacion_id' => $f->id,
                    'descuento_bonificacion_id' => $row->id
                ]);
            }
        };

        foreach ($formularios as $f) {
            $producto = Producto::whereRaw("(concat(letra,' | ',nombre) = ?)", [$f->producto])->first();
            $productoMinerales = $producto->productoMinerals;
            foreach ($productoMinerales as $row) {
                if(!$row->es_penalizacion) {
                    \DB::table('liquidacion_mineral')->insert([
                        'es_penalizacion' => $row->es_penalizacion,
                        'formulario_liquidacion_id' => $f->id,
                        'mineral_id' => $row->mineral_id
                    ]);
                }
            }
        };

        foreach ($formularios as $f) {
                \DB::table('costo')->insert([
                    'formulario_liquidacion_id' => $f->id,
                    'tratamiento' => 40,
                    'laboratorio' => 10,
                    'pesaje' => 10,
                    'comision' => 5,
                    'dirimicion' => 0,
                ]);
        };

        foreach ($formularios as $f) {
            $producto = Producto::whereRaw("(concat(letra,' | ',nombre) = ?)", [$f->producto])->first();
            $productoMinerales = $producto->productoMinerals;
            foreach ($productoMinerales as $row) {
                \DB::table('laboratorio')->insert([
                    [
                        'valor' => rand(10, 100),
                        'unidad' => $row->mineral->unidad_laboratorio,
                        'origen' => 'Empresa',
                        'formulario_liquidacion_id' => $f->id,
                        'mineral_id' => $row->mineral_id
                    ],
                    [
                        'valor' => rand(10, 100),
                        'unidad' => $row->mineral->unidad_laboratorio,
                        'origen' => 'Cliente',
                        'formulario_liquidacion_id' => $f->id,
                        'mineral_id' => $row->mineral_id
                    ],
                    [
                    'valor' => null,
                    'unidad' => $row->mineral->unidad_laboratorio,
                    'origen' => 'Dirimicion',
                    'formulario_liquidacion_id' => $f->id,
                    'mineral_id' => $row->mineral_id
                ]
                ]);
            }

            //humedad
            \DB::table('laboratorio')->insert([
                [
                    'valor' => rand(10, 100),
                    'unidad' => '%',
                    'origen' => 'Empresa',
                    'formulario_liquidacion_id' => $f->id,
                    'mineral_id' => null
                ],
                [
                    'valor' => rand(10, 100),
                    'unidad' => '%',
                    'origen' => 'Cliente',
                    'formulario_liquidacion_id' => $f->id,
                    'mineral_id' => null
                ],
                [
                    'valor' => null,
                    'unidad' => '%',
                    'origen' => 'Dirimicion',
                    'formulario_liquidacion_id' => $f->id,
                    'mineral_id' => null
                ]
            ]);
        };
        TipoTabla::create([
            'valor' => 0.5,
            'tabla' =>\App\Patrones\Tabla::Merma
        ]);
        TipoTabla::create([
            'valor' => 1.0,
            'tabla' => \App\Patrones\Tabla::Merma
        ]);

        //contrato
        Contrato::create([
            'porcentaje_arsenico' => 0.50, 'porcentaje_antimonio' => 0.50, 'porcentaje_bismuto' => 0.03, 'porcentaje_estanio' => 0.50,
            'porcentaje_hierro' => 0, 'porcentaje_silico' => 0, 'porcentaje_zinc' => 0, 'deduccion_elemento' => -8,
            'deduccion_plata' => -3, 'porcentaje_pagable_elemento' => 100, 'porcentaje_pagable_plata' => 70, 'maquila' => 265,
            'base' => 2550, 'escalador' => 0.150, 'deduccion_refinacion_onza' => 0, 'refinacion_libra_elemento' => 0,
            'laboratorio' => 0, 'molienda' => 0, 'manipuleo' => 0, 'margen_administrativo' => 0, 'transporte' => 36,
            'roll_back' => 32, 'producto_id' => 1
        ]);

        Contrato::create([
            'porcentaje_arsenico' => 0.50, 'porcentaje_antimonio' => 0.50, 'porcentaje_bismuto' => 0.07, 'porcentaje_estanio' => 0.50,
            'porcentaje_hierro' => 0, 'porcentaje_silico' => 0, 'porcentaje_zinc' => 0, 'deduccion_elemento' => -3,
            'deduccion_plata' => -1.5, 'porcentaje_pagable_elemento' => 95, 'porcentaje_pagable_plata' => 95, 'maquila' => 220,
            'base' => 1800, 'escalador' => 0.150, 'deduccion_refinacion_onza' => 2.2, 'refinacion_libra_elemento' => 0,
            'laboratorio' => 0, 'molienda' => 0, 'manipuleo' => 0, 'margen_administrativo' => 0, 'transporte' => 36,
            'roll_back' => 31, 'producto_id' => 2
        ]);

        TipoReporte::create([
            'nombre' => '*KARDEX*',
            'descripcion' => 'Muestra todos los campos'
        ]);

        LaboratorioQuimico::create([
            'nombre' => 'Laboratorio 1',
            'direccion' => 'Pagador',
        ]);
        LaboratorioQuimico::create([
            'nombre' => 'Laboratorio 2',
            'direccion' => 'Pagador',
        ]);

        LaboratorioPrecio::create([
            'monto' => 30,
            'producto_id' => 1,
            'laboratorio_quimico_id' => 1
        ]);
        LaboratorioPrecio::create([
            'monto' => 30,
            'producto_id' => 2,
            'laboratorio_quimico_id' => 1
        ]);
        LaboratorioPrecio::create([
            'monto' => 30,
            'producto_id' => 3,
            'laboratorio_quimico_id' => 1
        ]);
        LaboratorioPrecio::create([
            'monto' => 30,
            'producto_id' => 4,
            'laboratorio_quimico_id' => 1
        ]);

        LaboratorioPrecio::create([
            'monto' => 40,
            'producto_id' => 1,
            'laboratorio_quimico_id' => 2
        ]);

        LaboratorioPrecio::create([
            'monto' => 40,
            'producto_id' => 2,
            'laboratorio_quimico_id' => 2
        ]);
        LaboratorioPrecio::create([
            'monto' => 40,
            'producto_id' => 3,
            'laboratorio_quimico_id' => 2
        ]);
        LaboratorioPrecio::create([
            'monto' => 40,
            'producto_id' => 4,
            'laboratorio_quimico_id' => 2
        ]);


      /*   for ($i=0; $i <count(\App\Patrones\Fachada::getCamposReporte()); $i++){
            $valor['tipo_reporte_id'] = 1;
            $valor['nombre'] = \App\Patrones\Fachada::getCamposReporte()[$i];
            $valor['codigo'] = \App\Patrones\Fachada::getCodigosCamposReporte()[$i];

            $tipoReporte = CampoReporte::create($valor); */


            for ($i=0; $i <count(\App\Patrones\Fachada::getCamposReporte()); $i++){
                \DB::table('campo_reporte')->insert([
                    [
                        'tipo_reporte_id' => 1,
                        'nombre' => \App\Patrones\Fachada::getCamposReporte()[$i],
                        'codigo' => \App\Patrones\Fachada::getCodigosCamposReporte()[$i],
                    ],
                ]);
            }
        //}
    }
}
