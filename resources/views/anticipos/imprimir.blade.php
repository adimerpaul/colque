@include('anticipos.recibo')

@if($historial->count()<=3)
    <br><br><br><br><hr style="margin-top: -10px"><br>
    @include('anticipos.recibo')
@endif
