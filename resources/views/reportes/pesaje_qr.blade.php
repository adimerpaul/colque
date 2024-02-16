<head>
    <title>{{$formularioLiquidacion->lote}}</title>
</head>

@include('reportes.pesaje_qr_detalle', ['elemento' => 'H'])

@if($formularioLiquidacion->elemento=='Zn - Ag' OR $formularioLiquidacion->elemento=='Pb - Ag' OR $formularioLiquidacion->elemento=='Zn - Pb - Ag'
    OR $formularioLiquidacion->elemento=='Ag')
    @include('reportes.pesaje_qr_detalle', ['elemento' => 'H'])
@endif
@if($formularioLiquidacion->elemento=='Ag')
    @include('reportes.pesaje_qr_detalle', ['elemento' => $formularioLiquidacion->elemento])
@endif

@include('reportes.pesaje_qr_detalle', ['elemento' => $formularioLiquidacion->elemento])

@include('reportes.pesaje_qr_detalle', ['elemento' => $formularioLiquidacion->elemento])
@include('reportes.pesaje_qr_detalle', ['elemento' => $formularioLiquidacion->elemento])


