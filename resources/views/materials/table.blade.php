<div class="table-responsive">
    <table class="table table-striped" id="materials-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Símbolo</th>
            <th>Nombre</th>
            <th>Unidad para<br>Laboratorio</th>
            <th>Margen de error<br>para Laboratorio</th>
            <th>Cotización Diaria</th>
            <th>Cotización Oficial</th>
            <th></th>

        </tr>
        </thead>
        <tbody>
        @php
            $nro = 1;
        @endphp
        @foreach($materials as $material)
            <tr>
                <td>{{ $nro++ }}</td>
                <td>{{ $material->simbolo }}</td>
                <td>{{ $material->nombre }}</td>
                <td>{{ $material->unidad_laboratorio }}</td>
                <td>{{ $material->margen_error }}</td>
                <td>@if($material->ultima_cotizacion_diaria){{ $material->ultima_cotizacion_diaria->monto_form . ' ' .$material->ultima_cotizacion_diaria->unidad_form}}@endif</td>
                <td>@if($material->ultima_cotizacion_oficial){{ $material->ultima_cotizacion_oficial->monto . ' $us/' .$material->ultima_cotizacion_diaria->unidad}}@endif</td>

                <td style="width: 380px">
                    <div class='btn-group'>
                        @if(\App\Patrones\Fachada::tieneCotizacion() AND \App\Patrones\Permiso::esAdmin())
                            <a href="{{ route('materials.edit', [$material->id]) }}" class='btn btn-default btn-xs'><i
                                    class="glyphicon glyphicon-edit"></i> Editar</a>
                        @endif
                        @if($material->con_cotizacion)
                            <a href="{{ route('cotizacions.lista', [$material->id]) }}" class='btn btn-info btn-xs'><i
                                    class="glyphicon glyphicon-list"></i> Cotizacion diaria</a>
                            <a href="{{ route('cotizacionOficials.index', ['id' => $material->id]) }}"
                               class='btn btn-primary btn-xs'><i
                                    class="glyphicon glyphicon-list"></i> Cotizacion Oficial</a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
