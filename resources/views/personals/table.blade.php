<div class="table-responsive">
    <table class="table table-bordered table-striped" id="personals-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Ci</th>
            <th>Nombre Completo</th>
            <th>Celular</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Estado</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @php
            $nro = 1;
        @endphp
        @foreach($personals as $personal)
            <tr>
                <td>{{ $nro++ }}</td>
                <td>{{ $personal->ci }} {{ $personal->ci_add }} {{ $personal->expedido }}</td>
                <td>{{ $personal->nombre_completo }}</td>
                <td>{{ $personal->celular }}</td>
                <td>@if($personal->user){{ $personal->user->email }}@endif</td>
                <td>@if($personal->user){{ $personal->user->rol }}@endif</td>
                <td>
                    @if($personal->user)
                        @if($personal->user->alta)
                            <span class="label label-success">Alta</span>
                        @else
                            <span class="label label-danger">Baja</span>
                        @endif
                    @endif
                </td>
                <td>
                    <a  href="{{ route('tipo-horario-personal', ['id' => $personal->id]) }}" 
                        class="btn btn-default btn-xs">
                        <i class="glyphicon glyphicon-time" title="Tipo de Horario"></i>
                    </a>

                    @if($personal->user)
                        
                        @if(!$personal->user->itSeft)
                            {!! Form::open(['route' => ['personals.destroy', $personal->id], 'method' => 'delete']) !!}
                            <div class='btn-group'>
                                <a href="{{ route('personals.edit', [$personal->id, 'empresa' => $empresa->id]) }}"
                                   class='btn btn-default btn-xs'><i
                                        class="glyphicon glyphicon-edit"></i> Modificar</a>
                                
                                @if($personal->user->alta)
                                    {!! Form::button('<i class="glyphicon glyphicon-arrow-down"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Estas seguro de eliminar?')"]) !!}
                                @else
                                    {!! Form::button('<i class="glyphicon glyphicon-arrow-up"></i>', ['type' => 'submit', 'class' => 'btn btn-success btn-xs', 'onclick' => "return confirm('Estas seguro de eliminar?')"]) !!}
                                @endif
                                
                            </div>
                            {!! Form::close() !!}
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
