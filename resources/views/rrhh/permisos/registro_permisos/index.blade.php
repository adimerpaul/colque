@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>            <b>Mis permisos</b>       </h1>
        <h1 class="pull-right">
                <a class="btn btn-primary pull-right"
                   style="margin-top: -30px;margin-bottom: 5px margin-right: 3px"
                   href="{{ route('permisos.create') }}">Solicitar permiso</a>
        </h1>    
    </section>
   <div class="content">
   @include('flash::message')
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
               
               </div>
               <div class="table-responsive">
                    <table table class="table table-striped">
                            <thead class="table-red">
                            <tr>
                                <th scope="coll">#</th>
                                <th scope="coll">Fecha inicio</th>
                                <th scope="coll">Fecha final</th>
                                <th scope="coll">Tipo de permiso</th>
                                <th scope="coll">Motivo</th>
                                <th scope="coll">Estado</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($datos as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ \Carbon\Carbon::parse($item->inicio)->format('d/m/Y H:i:s') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->fin)->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $item->tipo}}</td>
                                <td>{{ $item->motivo}}</td>
                                <td>
                                    @if($item->es_aprobado)
                                        APROBADO
                                    @else
                                        PROCESANDO
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
               </div>

           </div>

       </div>
   </div>
@endsection
