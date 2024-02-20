<div class="col-sm-12 text-center">
    <strong>VALOR NETO DE LA VENTA</strong>
</div>

<div class="col-sm-11">
    <br>
    <table class="table table-bordered">
        <tr class="text-center">

            @if(\App\Patrones\Permiso::esAdmin() and ($formularioLiquidacion->letra=='B' or $formularioLiquidacion->letra=='D' or $formularioLiquidacion->letra=='E') )
                <td>{!! Form::label('ley_min', '¿Con ley mínima?') !!}
                    @if($formularioLiquidacion->con_ley_minima)
                        <input type="checkbox" checked name="con_ley_minima" id="con_ley_minima">
                    @else
                        <input type="checkbox" name="con_ley_minima" id="con_ley_minima">
                    @endif
                </td>
            @else
                <td>Peso neto seco: @{{ redondear(formulario.peso_neto_seco) }} KG</td>
            @endif
            <td>
                <div class="form-group form-inline" style="margin-bottom: 0px">
                    {!! Form::label('valor_por_tonelada', 'Valor por Tonelada:') !!}
                    @if( $formularioLiquidacion->letra =='D')
                        <input type="number" v-if="totalMinerales!=0" v-model="formulario.valor_por_tonelada"
                               name="valor_por_tonelada" disabled
                               id="valor_por_tonelada" required step="0.001">
                    @else
                        <input type="number" v-if="totalMinerales!=0" v-model="formulario.valor_por_tonelada"
                               name="valor_por_tonelada"
                               id="valor_por_tonelada" required step="0.001">
                    @endif

                    @if($formularioLiquidacion->esEscritura)
                        <button type="button" v-if="totalMinerales!=0" title="Actualizar valor por tonelada"
                                name="btActualizar" class="btn btn-sm btn-default" @click="actualizarValorPorTonelada">
                            <i class="glyphicon glyphicon-refresh"></i>
                        </button>
                        <button type="button" v-if="totalMinerales!=0 && formulario.valor_restado==false" title="Restar valor por tonelada"
                                name="btnRestarValor" id="btnRestarValor" class="btn btn-sm btn-info" @click="restarValorPorTonelada">
                            <i class="glyphicon glyphicon-minus"></i>
                        </button>

                    @endif
                    <strong v-if="totalMinerales==0"
                            style="background-color: #F44336;  padding:4px; color: white; font-size: 17px">QUEMADO</strong>
                </div>
            </td>
            <td>
                Valor neto de la venta: @{{ redondear(formulario.valor_neto_venta) }}
            </td>
        </tr>
    </table>
</div>
<div class="col-sm-1 text-right" style="padding-top: 50px">
    <strong>@{{ redondear(formulario.valor_neto_venta) }}</strong>

</div>


<div class="form-group col-sm-12 text-right">
    @if($formularioLiquidacion->esEscritura)
        <button type="submit" name="btnGuardar" class="btn btn-success">
            <i class="glyphicon glyphicon-floppy-disk"></i>
            GUARDAR
        </button>
    @endif
    <hr>
</div>


<div class="col-sm-12">
    <div class="text-left col-sm-5">
        <button @click="reiniciarDescuentos(formulario.id)" type="button" name="btnReiniciar" class="btn btn-info btn-sm">
            REINICIAR DESCUENTOS/BONIFICACIONES
        </button>
    </div>
    <div class="text-left col-sm-7">

        <button  type="button" class="btn btn-primary btn-sm" title="Agregar retenciones"
                style="pointer-events: none">
            <strong>(-) RETENCIONES DE LEY</strong></button>
    </div>

</div>

<div class="col-sm-11">
    <br>
    <table class="table table-bordered table-striped">

        @include('formulario_liquidacions.minerales')

        <tr v-for="(row, index) in retenciones" :key="index">
            <td style="width: 70px" v-if="esEscritura">
                <button type="button" class="btn btn-danger btn-xs"
                        @click="eliminarDescuento(row.descuento_bonificacion_id)">X
                </button>
            </td>
            <td>@{{ row.descuento_bonificacion.nombre }}</td>
            <td class="text-right" style="width: 120px">@{{ redondear(row.valor) }} @{{
                row.unidad === 'Porcentaje' ? '%' : ''
                }}
            </td>
            <td class="text-right" style="width: 120px">@{{ redondear(row.sub_total) }}</td>
        </tr>
    </table>
</div>
<div class="col-sm-1"></div>
<div class="col-sm-11 text-right">
    <strong>TOTAL RETENCIONES: </strong>
</div>
<div class="col-sm-1 text-right">
    <strong>@{{ formulario.totales !== undefined ? redondear(formulario.totales.total_retenciones) : 0 }}</strong>
</div>


<div class="col-sm-12 text-center">
    <button type="button" class="btn btn-primary btn-sm" title="Agregar bonificación" @click="openDescuentos">
        <strong>(-) DESCUENTOS INSTITUCIONALES</strong></button>
</div>

<div class="col-sm-11">
    <br>
    <table class="table table-bordered table-striped">
        <tr v-for="(row, index) in descuentos" :key="index">
            <td style="width: 70px" v-if="esEscritura">
                <button type="button" class="btn btn-danger btn-xs"
                        @click="eliminarDescuento(row.descuento_bonificacion_id)">X
                </button>

                @if(\App\Patrones\Permiso::esAdmin())
                    <button type="button" class="btn btn-warning btn-xs" data-target="#modalEditarDesc"
                            data-toggle="modal"
                            :data-txtid="row.id"
                            :data-txtnombre="row.descuento_bonificacion.nombre"
                            :data-txtvalor="row.valor">
                        <i class="fa fa-edit"></i>
                    </button>
                @endif
            </td>
            <td>@{{ row.descuento_bonificacion.nombre }}</td>
            <td class="text-right" style="width: 120px">@{{ redondear(row.valor) }} @{{
                row.unidad === 'Porcentaje' ? '%' : '' }}
            </td>
            <td class="text-right" style="width: 120px">@{{ redondear(row.sub_total) }}</td>
        </tr>
    </table>
</div>
<div class="col-sm-1"></div>
<div class="col-sm-11 text-right">
    <strong>TOTAL DESCUENTOS: </strong>
</div>
<div class="col-sm-1 text-right">
    <strong>@{{ redondear(totalDescuentos)}}</strong>
</div>

<div class="col-sm-12 text-center">
    <button type="button" class="btn btn-primary btn-sm" title="Agregar bonificación"
            @click="openBonificaciones">
        <strong>(+) BONIFICACIONES</strong></button>
</div>
<div class="col-sm-11">
    <br>
    <table class="table table-bordered table-striped">
        <tr v-for="(row, index) in bonificaciones" :key="index">
            <td style="width: 70px" v-if="esEscritura">
                <button type="button" class="btn btn-danger btn-xs"
                        @click="eliminarDescuento(row.descuento_bonificacion_id)">X
                </button>
                {{--                @if(\App\Patrones\Permiso::esComercial())--}}
                {{--                    <span v-if="row.descuento_bonificacion.nombre=='BONO PRODUCTOR'">--}}
                {{--                    <button type="button" class="btn btn-warning btn-xs" data-target="#modalEditarDesc"--}}
                {{--                            data-toggle="modal"--}}
                {{--                            :data-txtid="row.id"--}}
                {{--                            :data-txtnombre="row.descuento_bonificacion.nombre"--}}
                {{--                            :data-txtvalor="row.valor">--}}
                {{--                        <i class="fa fa-edit"></i>--}}
                {{--                    </button>--}}
                {{--                    </span>--}}
                {{--                    <span v-else>--}}
                @if(\App\Patrones\Permiso::esAdmin())
                    <button type="button" class="btn btn-warning btn-xs" data-target="#modalEditarDesc"
                            data-toggle="modal"
                            :data-txtid="row.id"
                            :data-txtnombre="row.descuento_bonificacion.nombre"
                            :data-txtvalor="row.valor">
                        <i class="fa fa-edit"></i>
                    </button>
                @endif
                {{--                    </span>--}}
                {{--                @endif--}}

            </td>

            <td>@{{ row.descuento_bonificacion.nombre }}
            </td>
            <td class="text-right" style="width: 120px">
                @{{ redondear(row.valor) }} @{{ row.unidad ===
                'Porcentaje' ? '%' : '' }}
            </td>
            <td class="text-right" style="width: 120px">@{{ redondear(row.sub_total) }}</td>
        </tr>
    </table>
</div>

<div class="col-sm-1"></div>
<div class="col-sm-11 text-right">
    <strong>TOTAL BONIFICACIONES: </strong>
</div>
<div class="col-sm-1 text-right">
    <strong>@{{ redondear(totalBonificaciones) }}</strong>
</div>

<div class="col-sm-12 text-right">
    <hr>
    <h4><strong>LIQUIDO PAGABLE: @{{ formulario.totales !== undefined ?
            redondear(formulario.totales.total_liquidacion) : 0 }}</strong></h4>
</div>


<div class="col-sm-11 text-right">
    <strong>ANTICIPOS: </strong>
</div>
<div class="col-sm-1 text-right">
    <strong>@{{ formulario.totales !== undefined ? redondear(formulario.totales.total_anticipos) : 0 }}</strong>
</div>

<div class="col-sm-11 text-right">
    <strong>DEVOLUCIONES: </strong>
</div>
<div class="col-sm-1 text-right">
    <strong>@{{ formulario.totales !== undefined ? redondear(formulario.totales.total_bonos) : 0 }}</strong>
</div>

<div class="col-sm-11 text-right">
    <strong>CUENTAS POR COBRAR: </strong>
</div>
<div class="col-sm-1 text-right">
    <strong>@{{ formulario.totales !== undefined ? redondear(formulario.totales.total_cuentas_cobrar) : 0 }}</strong>
</div>

<div class="col-sm-11 text-right" v-if="formulario.aporte_fundacion!=0">
    <strong>APORTE FUNDACIÓN COLQUECHACA: </strong>
</div>
<div class="col-sm-1 text-right" v-if="formulario.aporte_fundacion!=0">
    <strong>@{{ redondear(formulario.aporte_fundacion) }}</strong>
</div>

<div class="col-sm-12 text-right">
    <hr>
    <h3><strong>SALDO A FAVOR (BOB): @{{ formulario.totales !== undefined ?
            redondear(formulario.totales.total_saldo_favor) : 0 }}</strong></h3>
    <hr>
</div>


@include('formulario_liquidacions.add-descuento')

