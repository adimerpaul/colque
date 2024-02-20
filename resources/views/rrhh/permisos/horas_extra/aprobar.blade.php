@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Solicitud de Hora Extra del personal: <b>{{\App\Patrones\Fachada::getPersonal()[$horaExtra->personal_id]}}</b>
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        
                        <!-- Fechas inicio y fin-->
                        <div class="form-group col-sm-6">
                            {!! Form::label('fecha_inicio', 'Fecha Inicio:') !!}
                            <p>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $horaExtra->inicio)->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('fecha_fin', 'Fecha Final: ') !!}
                            <p>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $horaExtra->fin)->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('motivo', 'Motivo:') !!}
                            <p>
                                @if($horaExtra->descripcion){{$horaExtra->descripcion}}@else<br>@endif
                            </p>
                        </div>
                    {!! Form::model($horaExtra, ['route' => ['hora-extra.aprobado', $horaExtra->id], 'method' => 'patch']) !!}
                        <!-- Submit Field -->
                        <div class="col-md-12">
                            <div class="form-group col-sm-2">
                                {!! Form::submit('Aprobar', ['class' => 'btn btn-primary','onclick' => "return confirm('Estas seguro de aprobar la solicitud')"]) !!}
                            </div>   
                    {!! Form::close() !!} 

                            <div class="form-group col-sm-2">
                                {!! Form::open(['route' => ['horas-extras.destroy', $horaExtra->id], 'method' => 'delete']) !!}
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
