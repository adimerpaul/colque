@for($i = 1; $i <= ceil($ensayos->count()/ 30); $i++)
    @include('lab.ensayos.iteracion_informe')
@endfor
