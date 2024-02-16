@extends('layouts.app')

@section('content')
    <section class="content-header">
    <h1 class="pull-left"><b>Mis anticipos</b>
    </h1>
        <h1 class="pull-right">
            <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modalCrearAnticipo" style="margin-top: -10px;margin-bottom: 5px margin-right: 3px">
                Solicitar anticipo
            </a>    
        </h1>
   </section>
   <section class="content">   
   <div class="content">
       <div class="clearfix"></div>
       @include('flash::message')
       @include('adminlte-templates::common.errors')
       <div class="clearfix"></div>
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
               </div>
               <div class="table-responsive">
                    <table table class="table table-striped">
                            <thead class="table-red">
                            <tr>
                                <th scope="coll">#</th>
                                <th scope="coll">Monto</th>
                                <th scope="coll">Estado</th>
                                <th scope="coll">Cancelado</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($anticipos as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ $item->monto}}</td>
                                <td>
                                    @if($item->es_aprobado)
                                        APROBADO
                                    @else
                                        PROCESANDO
                                    @endif
                                </td>
                                <td>
                                    @if($item->es_cancelado)
                                        SI
                                    @else
                                        NO
                                    @endif
                                </td>
                                <td>{!! Form::open(['route' => ['anticipos-sueldos.destroy', $item->id], 'method' => 'delete']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('¿Estás seguro de eliminar?')"]) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
               </div>

           </div>

       </div>
   </div>
   </section>
   @include('rrhh.anticipos.modal_create')
@endsection
