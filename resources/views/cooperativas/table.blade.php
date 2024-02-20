<div class="table-responsive">
    <table class="table table-striped" id="cooperativas-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Nit</th>
            <th>Nro. Nim.</th>
            <th>Razón Social</th>
            <th>Municipio</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($cooperativas as $cooperativa)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $cooperativa->nit }}</td>
                <td>{{ $cooperativa->nro_nim }}</td>
                <td>{{ $cooperativa->razon_social }}</td>
                <td>@if($cooperativa->municipio) {{ $cooperativa->municipio->nombre }}@endif</td>

                <td>
                    <div class='btn-group'>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                Opciones<i style="margin-top: 3px" class="fa fa-angle-down pull-right"></i>
                            </button>
                            <div class="dropdown-menu">
                                @if(\App\Patrones\Permiso::esInvitado() or \App\Patrones\Permiso::esComercial())
                                    <a class='dropdown-item'
                                       href="{{ route('clientes.lista', [$cooperativa->id]) }}"
                                    >
                                        <i class="glyphicon glyphicon-list"></i>
                                        Clientes
                                    </a>

                                    <div class="dropdown-divider"></div>



                                @endif
                                @if(\App\Patrones\Permiso::esComercial())
                                        <a class='dropdown-item' href="{{ route('cooperativas.edit', [$cooperativa->id]) }}"
                                        >
                                            <i class="glyphicon glyphicon-edit"></i>
                                            Editar
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    <a class='dropdown-item'
                                       href="{{ route('descuentosBonificaciones.lista', [$cooperativa->id]) }}"
                                    >
                                        <i class="glyphicon glyphicon-list"></i>
                                        Descs/Bons
                                    </a>

                                    <div class="dropdown-divider"></div>

                                        <a class='dropdown-item'
                                           data-txtid="{{$cooperativa->id}}"
                                           href="#" data-toggle="modal" data-target="#modalAnalisisLaboratorio"
                                        >
                                            <i class="fa fa-flask"></i>
                                            Reporte Análisis
                                        </a>

                                        <div class="dropdown-divider"></div>


                                @endif
                                <a class='dropdown-item'
                                   href="{{ route('cooperativas.kardex', [$cooperativa->id]) }}"
                                >
                                    <i class="fa fa-file-text-o"></i>
                                    Kardex
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item"
                                   href="{{ route('cooperativas.reporte-rapido', [$cooperativa->id]) }}"
                                   target="_blank">
                                    <i class="fa fa-file-text"></i> Reporte rápido</a>
                                <div class="dropdown-divider"></div>

                                @if(\App\Patrones\Permiso::esContabilidad() or \App\Patrones\Permiso::esRrhh())
                                        <div class="dropdown-divider"></div>

                                        <a class='dropdown-item'
                                           href="{{ route('mostrar-documento-cooperativa', ['id' => $cooperativa->id]) }}"
                                        >
                                            <i class="fa fa-file"></i>
                                            Documentos
                                        </a>

                                        <div class="dropdown-divider"></div>

                                        <a class='dropdown-item'
                                           href="{{ route('cooperativas.reporte-contabilidad', ['id' => $cooperativa->id]) }}"
                                        >
                                            <i class="fa fa-money"></i>
                                            Reporte Contabilidad
                                        </a>

                                    <div class="dropdown-divider"></div>

                                    <a class='dropdown-item'
                                       href="{{ route('retenciones.lista', ['productorId' => $cooperativa->id]) }}"
                                    >
                                        <i class="glyphicon glyphicon-usd"></i>
                                        Retenciones
                                    </a>
                                @endif
                            </div>


                        </div>


                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br><br><br><br><br>
</div>
