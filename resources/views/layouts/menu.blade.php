<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ route('home') }}"><i class="fa fa-home"></i><span>Inicio</span></a>
</li>
@if(\App\Patrones\Fachada::cambioPass())


    @if(\App\Patrones\Permiso::esComercial() and \App\Patrones\Fachada::tieneCotizacion())
        <li class="{{ Request::is('cotizaciones-clientes*') ? 'active' : '' }}">
            <a href="{{ route('cotizaciones-clientes.index') }}"><i
                    class="fa fa-calculator"></i><span>Cotizador</span></a>
        </li>
    @endif
    @if(\App\Patrones\Permiso::esAdmin() )
        <li class="{{ Request::is('formularioLiquidacions*') ? 'active' : '' }}">
            <a href="{{ route('formularioLiquidacions.index') }}"><i class="fa fa-file"></i><span>Compras</span></a>
        </li>
    @else

        @if((\App\Patrones\Permiso::esComercial() || \App\Patrones\Permiso::esPesaje()|| \App\Patrones\Permiso::esContabilidad()|| \App\Patrones\Permiso::esRrhh()|| \App\Patrones\Permiso::esOperaciones())
            and \App\Patrones\Fachada::tieneCotizacion())
            <li class="{{ Request::is('formularioLiquidacions*') ? 'active' : '' }}">
                <a href="{{ route('formularioLiquidacions.index') }}"><i class="fa fa-file"></i><span>Compras</span></a>
            </li>
        @endif


    @endif

    @if(\App\Patrones\Permiso::esComercial())


        <li class="{{ Request::is('prestamos/create*') ? 'active' : '' }}">
            <a href="{{ route('prestamos.create') }}"><i
                    class="fa fa-handshake-o"></i><span>Préstamos</span></a>
        </li>


    @endif

    @if(\App\Patrones\Permiso::esComercial()|| \App\Patrones\Permiso::esContabilidad()|| \App\Patrones\Permiso::esRrhh()|| \App\Patrones\Permiso::esCaja())

        <li class="{{ Request::is('cuentas-cobrar-pendientes') ? 'active' : '' }}">
            <a href="{{ route('cuentas-cobrar-pendientes') }}"><i class="fa fa-warning"></i><span>Deudores</span></a>
        </li>

    @endif
    @if((\App\Patrones\Permiso::esComercial() || \App\Patrones\Permiso::esContabilidad()|| \App\Patrones\Permiso::esRrhh()|| \App\Patrones\Permiso::esOperaciones()|| \App\Patrones\Permiso::esActivos()) )
        <li class="{{ Request::is('movimientos/create*') ? 'active' : '' }}">
            <a href="{{ route('movimientos.create') }}"><i
                    class="fa fa-usd"></i><span>Pagos/Cobros</span></a>
        </li>
    @endif


    @if(\App\Patrones\Permiso::esOperaciones())
        <li class="{{ Request::is('ensayos*') ? 'active' : '' }}">
            <a href="{{ route('ensayos.index') }}"><i class="fa fa-flask"></i><span>Ensayos de laboratorio</span></a>
        </li>
    @endif
{{--    @if(\App\Patrones\Permiso::esInvitado())--}}
{{--        <li class="{{ Request::is('cooperativas*') ? 'active' : '' }}">--}}
{{--            <a href="{{ route('cooperativas.index') }}"><i--}}
{{--                    class="fa fa-institution"></i><span>Productores</span></a>--}}
{{--        </li>--}}
{{--    @endif--}}
    @if(\App\Patrones\Permiso::esComercial() AND App\Patrones\Fachada::tieneCotizacion())

        <li class="{{ Request::is('lotes/index*') ? 'active' : '' }}">
            <a href="{{ route('lotes.index') }}"><i class="fa fa-check-square-o"></i><span>Lotes a vender</span></a>
        </li>
    @endif

    @if(\App\Patrones\Permiso::esAdmin())
        <li class="{{ Request::is('ventas/index*') ? 'active' : '' }}">
            <a href="{{ route('ventas.index') }}"><i class="fa fa-dollar"></i><span>Ventas</span></a>
    @else
        @if((\App\Patrones\Permiso::esComercial() || \App\Patrones\Permiso::esContabilidad()|| \App\Patrones\Permiso::esRrhh()|| \App\Patrones\Permiso::esOperaciones())
            AND App\Patrones\Fachada::tieneCotizacion())
            <li class="{{ Request::is('ventas/index*') ? 'active' : '' }}">
                <a href="{{ route('ventas.index') }}"><i class="fa fa-dollar"></i><span>Ventas</span></a>
        @endif
    @endif

    @if(\App\Patrones\Permiso::esCaja() OR \App\Patrones\Permiso::esContabilidad()|| \App\Patrones\Permiso::esRrhh()|| \App\Patrones\Permiso::esOperaciones())
        <li class="treeview {{ Request::is('retenciones-pagos*') || Request::is('cajas*') ? 'active' : '' }}">
            <a href="#"><i class="fa fa-usd"></i>
                <span>Caja</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Request::is('pagos/anticipos') ? 'active' : '' }}">
                    <a href="{{ route('pagos.anticipos') }}"><i
                            class="fa fa-arrow-right"></i><span>Anticipos</span></a>
                </li>
                <li class="{{ Request::is('pagos-devoluciones') ? 'active' : '' }}">
                    <a href="{{ route('pagos.devoluciones') }}"><i
                            class="fa fa-arrow-left"></i><span>Devoluciones</span></a>
                </li>
                <li class="{{ Request::is('cajas*') ? 'active' : '' }}">
                    <a href="{{ route('cajas.index') }}"><i
                            class="fa fa-credit-card"></i><span>Liquidaciones</span></a>
                </li>
                <li class="{{ Request::is('retenciones-pagos*') ? 'active' : '' }}">
                    <a href="{{ route('retenciones-pagos.index') }}"><i
                            class="fa fa-credit-card-alt"></i><span>Retenciones</span></a>
                </li>
                <li class="{{ Request::is('pagos-cuentas-cobrar*') ? 'active' : '' }}">
                    <a href="{{ route('pagos.cuentas') }}"><i
                            class="fa fa-usd"></i><span>Cuentas por cobrar</span></a>
                </li>

                <li class="{{ Request::is('movimientos*') ? 'active' : '' }}">
                    <a href="{{ route('movimientos.index') }}"><i
                            class="fa fa-money"></i><span>Pagos a terceros</span></a>
                </li>

                <li class="{{ Request::is('pagos-dolares*') ? 'active' : '' }}">
                    <a href="{{ route('pagos-dolares.index') }}"><i
                            class="fa fa-usd"></i><span>Cuenta en dólares</span></a>
                </li>

                <li class="{{ Request::is('prestamos*') ? 'active' : '' }}">
                    <a href="{{ route('prestamos.index') }}"><i
                            class="fa fa-handshake-o"></i><span>Préstamos</span></a>
                </li>
                <li class="{{ Request::is('pagos-ventas*') ? 'active' : '' }}">
                    <a href="{{ route('ventas.caja') }}"><i
                            class="fa fa-cc"></i><span>Ventas</span></a>
                </li>
                <li class="{{ Request::is('cobros/anticipos-ventas*') ? 'active' : '' }}">
                    <a href="{{ route('anticipos_ventas.pagos') }}"><i
                            class="fa fa-arrow-left"></i><span>Anticipos Ventas</span></a>
                </li>
                <li class="{{ Request::is('reporte-pagos*') ? 'active' : '' }}">
                    <a href="{{ route('movimientos.reporte') }}"><i
                            class="fa fa-list-ul"></i><span>Reportes</span></a>
                </li>

                @if(\App\Patrones\Permiso::esContabilidad()|| \App\Patrones\Permiso::esRrhh()|| \App\Patrones\Permiso::esOperaciones())
                    <li class="{{ Request::is('lista-pagos*') ? 'active' : '' }}">
                        <a href="{{ route('movimientos.lista-pagos') }}"><i
                                class="fa fa-list"></i><span>Últimos movimientos</span></a>
                    </li>
                @endif

            </ul>
        </li>
    @endif
    @if(\App\Patrones\Permiso::esComercial() || \App\Patrones\Permiso::esContabilidad()|| \App\Patrones\Permiso::esRrhh()|| \App\Patrones\Permiso::esOperaciones())
        <li class="{{ Request::is('kardex*') ? 'active' : '' }}">
            <a href="{{ route('formularioLiquidacions.kardex') }}"><i
                    class="fa fa-table"></i><span>Reporte Kardex</span></a>
        </li>
    @endif
    @if( \App\Patrones\Permiso::esContabilidad())
        <li class="{{ Request::is('lab/informes-colquechaca*') ? 'active' : '' }}">
            <a href="{{ route('get-informes-colquechaca-lab') }}"><i
                    class="fa fa-flask"></i><span>Informes Laboratorio</span></a>
        </li>
    @endif
    @if(\App\Patrones\Permiso::esRrhh())
        <li class="treeview">
                <a href="#"><i class="fa fa-users"></i>
                    <span>RRHH</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ route('asistencias.index') }}"><i class="fa fa-list"></i>
                        <span>Asistencia</span></a>
                    </li>

                    <li>
                        <a href="{{ route('mostrarpermisos.general') }}"><i class="fa fa-check-circle-o"></i>
                        <span>Permisos</span></a>
                    </li>
                    <li >
                        <a href="{{ route('horas-extras.index') }}"><i class="fa fa-hourglass"></i>
                            </i><span>Horas Extra</span></a>
                    </li>
                    <li>
                        <a href="{{ route('crear.asistencia') }}"><i class="fa fa-id-card-o"></i>
                        </i><span>Crear Asistencia</span></a>
                    </li>
                    <li>
                        <a href="{{ route('tipoHorario') }}"><i class="fa fa-plus-square-o"></i>
                        </i><span>Tipos horarios</span></a>
                    </li>
                    <li>
                        <a href="{{ route('calendario.index') }}"><i
                                class="fa fa-calendar"></i><span>Calendario</span></a>
                    </li>
                </ul>
        </li>
    @endif
    @if(((\App\Patrones\Permiso::esComercial()) || \App\Patrones\Permiso::esContabilidad()|| \App\Patrones\Permiso::esRrhh()|| \App\Patrones\Permiso::esOperaciones()|| \App\Patrones\Permiso::esActivos()))
        <li class="treeview {{ Request::is('tipoReportes*') || Request::is('users*')  || Request::is('choferes*') || Request::is('vehiculos*') || Request::is('cooperativas*') || Request::is('proveedores*') ? 'active' : '' }}">
            <a href="#"><i class="fa fa-gear"></i>
                <span>Configuraciones</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @if(\App\Patrones\Permiso::esComercial() || \App\Patrones\Permiso::esContabilidad()|| \App\Patrones\Permiso::esRrhh())
                    <li class="{{ Request::is('cooperativas*') ? 'active' : '' }}">
                        <a href="{{ route('cooperativas.index') }}"><i
                                class="fa fa-institution"></i><span>Productores</span></a>
                    </li>
                    <li class="{{ Request::is('tipoReportes*') ? 'active' : '' }}">
                        <a href="{{ route('tipoReportes.index') }}"><i
                                class="fa fa-list-ul"></i><span>Tipos de reportes</span></a>
                    </li>
                @endif
                @if(\App\Patrones\Permiso::esOperaciones())
                    <li class="{{ Request::is('choferes*') ? 'active' : '' }}">
                        <a href="{{ route('choferes.index') }}"><i
                                class="fa fa-id-card"></i><span>Conductores</span></a>
                    </li>

                    <li class="{{ Request::is('vehiculos*') ? 'active' : '' }}">
                        <a href="{{ route('vehiculos.index') }}"><i class="fa fa-car"></i><span>Vehiculos</span></a>
                    </li>
                @endif
                @if(\App\Patrones\Permiso::esComercial())
                        <li class="{{ Request::is('materials*') ||  Request::is('cotizacions*') ? 'active' : '' }}">
                            <a href="{{ route('materials.index') }}"><i class="fa fa-diamond"></i><span>Minerales</span></a>
                        </li>

                    <li class="{{ Request::is('compradores*') ? 'active' : '' }}">
                        <a href="{{ route('compradores.index') }}"><i
                                class="fa fa-building"></i><span>Compradores</span></a>
                    </li>
                    <li class="{{ Request::is('tablaAcopiadoras*') ? 'active' : '' }}">
                        <a href="{{ route('tablaAcopiadoras.index') }}"><i class="fa fa-table"></i><span>Tabla Acopiadora (Sn)</span></a>
                    </li>

                    <li class="{{ Request::is('contratos*') ? 'active' : '' }}">
                        <a href="{{ route('contratos.index') }}"><i
                                class="fa fa-money"></i><span>Contratos (Pb, Zn)</span></a>
                    </li>

                @endif

                @if(\App\Patrones\Permiso::esAdmin())

                    <li class="{{ Request::is('empresas*') ? 'active' : '' }}">
                        @if(\App\Patrones\Permiso::esSuperAdmin())
                            <a href="{{ route('empresas.index') }}"><i
                                    class="fa fa-users"></i><span>Empresas - Usuarios</span></a>
                        @elseif(\App\Patrones\Permiso::esAdmin())
                            <a href="{{ route('empresas.show', [auth()->user()->personal->empresa->id]) }}"><i
                                    class="fa fa-users"></i><span>Empresas - Usuarios</span></a>
                        @endif
                    </li>

                    <li class="{{ Request::is('productos*') ? 'active' : '' }}">
                        <a href="{{ route('productos.index') }}"><i
                                class="fa fa-object-group"></i><span>Productos</span></a>
                    </li>

                        <li class="{{ Request::is('tipoCambios*') ? 'active' : '' }}">
                            <a href="{{ route('tipoCambios.index') }}"><i class="fa fa-money"></i><span>Tipo de cambios</span></a>
                        </li>


                    <li class="{{ Request::is('laboratorioQuimicos*') ? 'active' : '' }}">
                        <a href="{{ route('laboratorioQuimicos.index') }}"><i class="fa fa-flask"></i><span>Laboratorios químicos</span></a>
                    </li>


                @endif
                    @if(\App\Patrones\Permiso::esRrhh())

                        <li class="{{ Request::is('movimientos-catalogos*') ? 'active' : '' }}">
                            <a href="{{ route('movimientos-catalogos.index') }}"><i class="fa fa-usd"></i><span>Cuentas Movimientos</span></a>
                        </li>
                    @endif

                @if(\App\Patrones\Permiso::esJefe())

                    <li class="{{ Request::is('proveedores*') ? 'active' : '' }}">
                        <a href="{{ route('proveedores.index') }}"><i
                                class="fa fa-user-circle"></i><span>Proveedores</span></a>
                @endif
                    <li class="{{ Request::is('activos-fijos*') ? 'active' : '' }}">
                        <a href="{{ route('activos-fijos.index') }}"><i
                                class="fa fa-desktop"></i><span>Activos Fijos</span></a>
                    </li>
            </ul>
        </li>
    @endif


    <li class="treeview">
        <a href="#"><i class="fa fa-gear"></i>
            <span>Impuestos</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
                <li>
                    <a href="{{ route('cooperativas.index') }}"><i
                            class="fa fa-institution"></i><span>Sincronizacion</span></a>
                </li>
            <li>
                <a href="{{ route('cooperativas.index') }}"><i
                        class="fa fa-institution"></i><span>Compra Y venta</span></a>
            </li>
            <li>
                <a href="{{ route('cooperativas.index') }}"><i
                        class="fa fa-institution"></i><span>Exportacion Mineral</span></a>
            </li>
            <li>
                <a href="{{ route('evento.index') }}">
                    <i class="fa fa-institution"></i>
                    <span>Evento Significativo</span>
                </a>
            </li>
        </ul>
    </li>




    <br><br>
    {{--    @if(\App\Patrones\Permiso::esContabilidad()|| \App\Patrones\Permiso::esOperaciones())--}}
    {{--        <li class="treeview {{ Request::is('cooperativas*') || Request::is('tipoReportes*') ? 'active' : '' }}">--}}
    {{--            <a href="#"><i class="fa fa-gear"></i>--}}
    {{--                <span>Configuraciones</span>--}}
    {{--                <span class="pull-right-container">--}}
    {{--                <i class="fa fa-angle-left pull-right"></i>--}}
    {{--            </span>--}}
    {{--            </a>--}}
    {{--            <ul class="treeview-menu">--}}
    {{--                <li class="{{ Request::is('cooperativas*') ? 'active' : '' }}">--}}
    {{--                    <a href="{{ route('cooperativas.index') }}"><i--}}
    {{--                            class="fa fa-institution"></i><span>Productores</span></a>--}}
    {{--                </li>--}}
    {{--                <li class="{{ Request::is('tipoReportes*') ? 'active' : '' }}">--}}
    {{--                    <a href="{{ route('tipoReportes.index') }}"><i--}}
    {{--                            class="fa fa-list-ul"></i><span>Tipos de reportes</span></a>--}}
    {{--                </li>--}}
    {{--            </ul>--}}
    {{--        </li>--}}
    {{--    @endif--}}
@endif
