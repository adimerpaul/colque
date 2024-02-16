@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>            <b>Mis asistencias</b>       </h1>
   </section>
   <div class="content">
   @include('flash::message')
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                    <div class="col-sm-12">
                        <div>
                                        {!! Form::label('txtBuscar', 'Buscar por:') !!}
                                        {!! Form::open(['route' => 'mis-asistencias', 'method' => 'get']) !!}
                                            <div class="form-group col-sm-3">
                                                    {!! Form::label('fecha_i', 'Fecha Inicial:') !!}
                                                    {!! Form::date('fecha_i', isset($_GET['fecha_i']) ? $_GET['fecha_i'] : date('Y-m-01'), ['class' => 'form-control', 'required']) !!}
                                            </div>
                                            <div class="form-group col-sm-3">
                                                {!! Form::label('fecha_f', 'Fecha Final:') !!}
                                                {!! Form::date('fecha_f', old('fecha_f', isset($_GET['fecha_f']) ? $_GET['fecha_f'] : date('Y-m-d')), ['class' => 'form-control']) !!}
                                            </div>
                                            <div class="form-group col-sm-1" style="margin-top: 24px">
                                                <button type="submit" class="btn btn-default glyphicon glyphicon-search" title="Buscar Datos"></button>
                                            </div>
                                        {!! Form::close() !!}
                        </div>
                    </div>    
               </div>
               <div class="table-responsive">
                <table class="table table-striped" id="mis-asistencias" name="mis-asistencias">
                        <thead class="table-red">
                        <tr>
                            <th scope="coll">#</th>
                            <th scope="coll">Fecha Marcada</th>
                            <th scope="coll">Atrasos</th>
                            <th scope="coll">Horas extra</th>
                            <th scope="coll">Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($datos as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ \Carbon\Carbon::parse($item->hora_marcada)->format('d/m/Y H:i:s') }}</td>
                                <td>{{$item->atraso}}</td>
                                <td>{{ \Carbon\Carbon::parse($item->hora_marcada)->format('H:i')<'12:00' ? $item->hora_extra_manana : $item->hora_extra_tarde }}</td>
                                <td>{!! \App\Patrones\Fachada::estadoAsistencia($item->tipo_asistencia,$item->control_asistencia) !!}
                                </td>
                            </tr>
                            @endforeach
                            @if($datos->count()==0)
                                <tr>
                                    <td colspan="4" class="text-center">No se actualizó la tabla de asistencia de las fechas seleccionadas </td>
                                </tr>
                            @endif
                            <tr>
                                <td>
                                    <b style="text-align: center">
                                        TOTALES
                                    </b>
                                </td>
                                <td></td>
                                <td><b>
                                    {{$datos->sum("sumatoria_atrasos_minutos"). " min"}}
                                </b></td>

                                <td><b>{{$datos->sum("sumatoria_horas_extras"). " min"}}</b></td>
                            </tr>
                        </tbody>
                    </table>       
                </div>

           </div>
            <div class="text-center">
                    {{ $datos->appends($_GET)->links() }}
            </div>
       </div>
   </div>
<script>
    var table=document.getElementById("mis-asistencias"), sumaAtrasos=0,sumaHoraExtra=0;
    
    for(var i = 1; i < (table.rows.length - 1); i++){
        
        //console.log(table.rows[i].cells[2].innerHTML);
        let valorAtrasos=table.rows[i].cells[2].innerHTML
        sumaAtrasos=sumaAtrasos + Number(valorAtrasos);
    }
    console.log(sumaAtrasos);
    
    

</script>
@endsection
