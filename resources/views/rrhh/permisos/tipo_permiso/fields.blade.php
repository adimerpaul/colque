<div class="form-group col-sm-12">
    {!! Form::label('personal_id', 'Personal a Registrar:*') !!}
    {!! Form::select('personal_id', [null => 'Seleccione...'] + \App\Patrones\Fachada::getPersonal(), nulL, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    <div class="form-group">
        {!! Form::label('tipos', 'Tipos: *') !!}
        <table class="table">
            <thead>
                <tr>
                    <th>Seleccionar</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                @foreach (\App\Patrones\Fachada::todosTiposPermisos() as $id => $descripcion)
                    <tr>
                        <td>
                            {!! Form::checkbox('tipo_permiso_id[]', $id, null, ['class' => 'form-check-input']) !!}
                        </td>
                        <td>{{ $descripcion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> 

<div class="form-group col-sm-12">
    {!! Form::submit('Crear', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('mostrarpermisos.general') }}" class="btn btn-default">Volver</a>
</div>




