@include('anticipos_ventas.recibo')

@if($historial->count()<=3)
    <br><br><hr style="margin-top: -10px"><br>
    @include('anticipos_ventas.recibo')
@endif
