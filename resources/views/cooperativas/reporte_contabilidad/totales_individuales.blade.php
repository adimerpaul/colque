@if($formularios->where('letra','A')->count()>0)
    <tr>
        <td colspan="3" class="text-center" style=" border: 1px solid black;">
            <b style="text-align: center">
                A
            </b>
        </td>
        <td style=" border: 1px solid black;">
            <b> {{ round($formularios->where('letra','A')->sum('neto_venta'),2)}}</b></td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','A')->sum('regalia_minera'),2)}}</td>
        @if($nroRetenciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroRetenciones}}"></td>@endif
        @if($nroDescuentos >0) <td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroDescuentos}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','A')->sum('total_retencion_descuento'),2)}}</td>
        @if($nroBonificaciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroBonificaciones}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','A')->sum('total_bonificacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','A')->sum('liquido_pagable'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','A')->sum('total_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','A')->sum('cuentas_saldo_negativo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','A')->sum('cuentas_prestamo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','A')->sum('cuentas_retiro'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','A')->sum('aporte_fundacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','A')->sum('devolucion_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','A')->sum('devolucion_laboratorio'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','A')->where('saldo_favor','>', '0.00')->sum('saldo_favor'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
    </tr>
@endif

@if($formularios->where('letra','B')->count()>0)
    <tr>
        <td colspan="3" class="text-center" style=" border: 1px solid black;">
            <b style="text-align: center">
                B
            </b>
        </td>
        <td style=" border: 1px solid black;">
            <b> {{ round($formularios->where('letra','B')->sum('neto_venta'),2)}}</b></td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','B')->sum('regalia_minera'),2)}}</td>
        @if($nroRetenciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroRetenciones}}"></td>@endif
        @if($nroDescuentos >0) <td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroDescuentos}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','B')->sum('total_retencion_descuento'),2)}}</td>
        @if($nroBonificaciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroBonificaciones}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','B')->sum('total_bonificacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','B')->sum('liquido_pagable'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','B')->sum('total_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','B')->sum('cuentas_saldo_negativo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','B')->sum('cuentas_prestamo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','B')->sum('cuentas_retiro'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','B')->sum('aporte_fundacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','B')->sum('devolucion_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','B')->sum('devolucion_laboratorio'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','B')->where('saldo_favor','>', '0.00')->sum('saldo_favor'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
    </tr>
@endif

@if($formularios->where('letra','C')->count()>0)
    <tr>
        <td colspan="3" class="text-center" style=" border: 1px solid black;">
            <b style="text-align: center">
                C
            </b>
        </td>
        <td style=" border: 1px solid black;">
            <b> {{ round($formularios->where('letra','C')->sum('neto_venta'),2)}}</b></td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','C')->sum('regalia_minera'),2)}}</td>
        @if($nroRetenciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroRetenciones}}"></td>@endif
        @if($nroDescuentos >0) <td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroDescuentos}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','C')->sum('total_retencion_descuento'),2)}}</td>
        @if($nroBonificaciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroBonificaciones}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','C')->sum('total_bonificacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','C')->sum('liquido_pagable'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','C')->sum('total_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','C')->sum('cuentas_saldo_negativo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','C')->sum('cuentas_prestamo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','C')->sum('cuentas_retiro'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','C')->sum('aporte_fundacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','C')->sum('devolucion_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','C')->sum('devolucion_laboratorio'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','C')->where('saldo_favor','>', '0.00')->sum('saldo_favor'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
    </tr>
@endif


@if($formularios->where('letra','D')->count()>0)
    <tr>
        <td colspan="3" class="text-center" style=" border: 1px solid black;">
            <b style="text-align: center">
                D
            </b>
        </td>
        <td style=" border: 1px solid black;">
            <b> {{ round($formularios->where('letra','D')->sum('neto_venta'),2)}}</b></td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','D')->sum('regalia_minera'),2)}}</td>
        @if($nroRetenciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroRetenciones}}"></td>@endif
        @if($nroDescuentos >0) <td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroDescuentos}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','D')->sum('total_retencion_descuento'),2)}}</td>
        @if($nroBonificaciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroBonificaciones}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','D')->sum('total_bonificacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','D')->sum('liquido_pagable'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','D')->sum('total_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','D')->sum('cuentas_saldo_negativo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','D')->sum('cuentas_prestamo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','D')->sum('cuentas_retiro'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','D')->sum('aporte_fundacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','D')->sum('devolucion_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','D')->sum('devolucion_laboratorio'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','D')->where('saldo_favor','>', '0.00')->sum('saldo_favor'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
    </tr>
@endif

@if($formularios->where('letra','E')->count()>0)
    <tr>
        <td colspan="3" class="text-center" style=" border: 1px solid black;">
            <b style="text-align: center">
                E
            </b>
        </td>
        <td style=" border: 1px solid black;">
            <b> {{ round($formularios->where('letra','E')->sum('neto_venta'),2)}}</b></td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','E')->sum('regalia_minera'),2)}}</td>
        @if($nroRetenciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroRetenciones}}"></td>@endif
        @if($nroDescuentos >0) <td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroDescuentos}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','E')->sum('total_retencion_descuento'),2)}}</td>
        @if($nroBonificaciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroBonificaciones}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','E')->sum('total_bonificacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','E')->sum('liquido_pagable'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','E')->sum('total_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','E')->sum('cuentas_saldo_negativo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','E')->sum('cuentas_prestamo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','E')->sum('cuentas_retiro'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','E')->sum('aporte_fundacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','E')->sum('devolucion_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','E')->sum('devolucion_laboratorio'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','E')->where('saldo_favor','>', '0.00')->sum('saldo_favor'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
    </tr>
@endif

@if($formularios->where('letra','F')->count()>0)
    <tr>
        <td colspan="3" class="text-center" style=" border: 1px solid black;">
            <b style="text-align: center">
                F
            </b>
        </td>
        <td style=" border: 1px solid black;">
            <b> {{ round($formularios->where('letra','F')->sum('neto_venta'),2)}}</b></td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','F')->sum('regalia_minera'),2)}}</td>
        @if($nroRetenciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroRetenciones}}"></td>@endif
        @if($nroDescuentos >0) <td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroDescuentos}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','F')->sum('total_retencion_descuento'),2)}}</td>
        @if($nroBonificaciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroBonificaciones}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','F')->sum('total_bonificacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','F')->sum('liquido_pagable'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','F')->sum('total_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','F')->sum('cuentas_saldo_negativo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','F')->sum('cuentas_prestamo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','F')->sum('cuentas_retiro'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','F')->sum('aporte_fundacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','F')->sum('devolucion_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','F')->sum('devolucion_laboratorio'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','F')->where('saldo_favor','>', '0.00')->sum('saldo_favor'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
    </tr>
@endif

@if($formularios->where('letra','G')->count()>0)
    <tr>
        <td colspan="3" class="text-center" style=" border: 1px solid black;">
            <b style="text-align: center">
                G
            </b>
        </td>
        <td style=" border: 1px solid black;">
            <b> {{ round($formularios->where('letra','G')->sum('neto_venta'),2)}}</b></td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','G')->sum('regalia_minera'),2)}}</td>
        @if($nroRetenciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroRetenciones}}"></td>@endif
        @if($nroDescuentos >0) <td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroDescuentos}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','G')->sum('total_retencion_descuento'),2)}}</td>
        @if($nroBonificaciones >0)<td style=" border: 1px solid black; font-weight: bold" colspan="{{$nroBonificaciones}}"></td>@endif
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','G')->sum('total_bonificacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','G')->sum('liquido_pagable'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','G')->sum('total_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','G')->sum('cuentas_saldo_negativo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','G')->sum('cuentas_prestamo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','G')->sum('cuentas_retiro'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','G')->sum('aporte_fundacion'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','G')->sum('devolucion_anticipo'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','G')->sum('devolucion_laboratorio'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold">{{ round($formularios->where('letra','G')->where('saldo_favor','>', '0.00')->sum('saldo_favor'),2)}}</td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
        <td style=" border: 1px solid black; font-weight: bold"></td>
    </tr>
@endif
