@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Permiso pendiente del personal: <b>{{\App\Patrones\Fachada::getPersonal()[$permiso->personal_id]}}</b>
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                    {!! Form::model($permiso, ['route' => ['permiso.aprobado', $permiso->id], 'method' => 'patch']) !!}
                        <div class="form-group col-sm-6">
                            {!! Form::label('tipo', 'Tipo: ') !!}
                            <p>{{\App\Patrones\Fachada::getTiposPermisos()[$permiso->tipo] }} </p>
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('motivo', 'Motivo:') !!}

                            <p>
                                @if($permiso->motivo){{$permiso->motivo}}@else<br>@endif
                            </p>
                        </div>
                        <!-- Fechas inicio y fin-->
                        <div class="form-group col-sm-6">
                            {!! Form::label('fecha_inicio', 'Fecha Inicio:') !!}
                            <p>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $permiso->inicio)->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('fecha_fin', 'Fecha Final: ') !!}
                            <p>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $permiso->fin)->format('d/m/Y H:i:s') }}</p>

                        </div>

                        <!-- Submit Field -->
                        <div class="col-md-12">
                            <div class="form-group col-sm-2">
                                {!! Form::submit('Aprobar', ['class' => 'btn btn-primary','onclick' => "return confirm('Estas seguro de aprobar la solicitud')"]) !!}
                            </div>
                    {!! Form::close() !!}

                            <div class="form-group col-sm-2">
                                {!! Form::open(['route' => ['permisos.rechazados', $permiso->id], 'method' => 'delete']) !!}
                                {!! Form::button('Rechazar', ['type' => 'submit', 'class' => 'btn btn-default', 'onclick' => "return confirm('Estas seguro de rechazar la solicitud')"]) !!}
                                {!! Form::close() !!}

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
