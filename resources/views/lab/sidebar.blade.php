<aside class="main-sidebar" id="sidebar-wrapper">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->personal->nombre_completo }}" class="img-circle"
                     alt="User Image"/>
            </div>
            <div class="pull-left info">
                @if (Auth::guest())
                    <p>Colquechaca</p>
                @else
                    <p>{{ strtok(Auth::user()->personal->nombre_completo, " ") }}</p>
            @endif
            <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i>{{ auth()->user()->rol }}</a>
            </div>
        </div>


        <!-- Sidebar Menu -->

        <ul class="sidebar-menu" data-widget="tree">
            <li class="{{ Request::is('inicio-lab*') ? 'active' : '' }}">
                <a href="{{ route('inicio-lab') }}"><i class="fa fa-home"></i><span>Inicio</span></a>
            </li>

            <li class="{{ Request::is('clientes-lab*') ? 'active' : '' }}">
                <a href="{{ route('clientes-lab.index') }}"><i
                        class="fa fa-user"></i><span>Clientes</span></a>
            </li>

            <li class="{{ Request::is('recepcion-lab*') ? 'active' : '' }}">
                <a href="{{ route('recepcion-lab.index') }}"><i
                        class="fa fa-list"></i><span>Recepción</span></a>
            </li>

            <li class="{{ Request::is('proveedores-lab*') ? 'active' : '' }}">
                <a href="{{ route('proveedores-lab.index') }}"><i
                        class="fa fa-user-circle"></i><span>Proveedores</span></a>
            </li>
            <li class="{{ Request::is('lab/factores-volumetricos*') ? 'active' : '' }}">
                <a href="{{ route('factores-volumetricos.index') }}"># &nbsp; <span> Factor Volumétrico</span></a>
            </li>

            <li class="{{ Request::is('cajas-lab*') ? 'active' : '' }}">
                <a href="{{ route('cajas-lab.index') }}"><i
                        class="fa fa-usd"></i><span>Caja</span></a>
            </li>

            <li class="{{ Request::is('accidentes*') ? 'active' : '' }}">
                <a href="{{ route('accidentes.index') }}"><i
                        class="fa fa-warning"></i><span>Accidentes</span></a>
            </li>

                <li class="treeview {{ Request::is('lab/get-*') ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-table"></i>
                        <span>Reportes</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">

                        <li class="{{ Request::is('lab/get-aceptados*') ? 'active' : '' }}">
                            <a href="{{ route('get-aceptados-lab') }}"><i
                                    class="fa fa-check"></i><span>Aceptados</span></a>
                        </li>
                        <li class="{{ Request::is('lab/get-rechazados*') ? 'active' : '' }}">
                            <a href="{{ route('get-rechazados-lab') }}"><i
                                    class="fa fa-remove"></i><span>Rechazados</span></a>
                        </li>

                        <li class="{{ Request::is('lab/get-finalizados*') ? 'active' : '' }}">
                            <a href="{{ route('get-finalizados-lab') }}"><i
                                    class="fa fa-flask"></i><span>Finalizados</span></a>
                        </li>

                        <li class="{{ Request::is('lab/get-ingresos*') ? 'active' : '' }}">
                            <a href="{{ route('get-ingresos-lab') }}"><i
                                    class="fa fa-calculator"></i><span>Ingresos</span></a>
                        </li>
                        <li class="{{ Request::is('lab/get-egresos*') ? 'active' : '' }}">
                            <a href="{{ route('get-egresos-lab') }}"><i
                                    class="fa fa-calculator"></i><span>Egresos</span></a>
                        </li>
                        <li class="{{ Request::is('lab/insumos*') ? 'active' : '' }}">
                            <a href="{{ route('insumos.index') }}"><i
                                    class="fa fa-flask"></i><span>Insumos</span></a>
                        </li>

                        <li class="{{ Request::is('lab/get-tecnico*') ? 'active' : '' }}">
                            <a
                               href="{{ url('lab/get-tecnico?elemento_id=2') }}"><i
                                    class="fa fa-flask"></i><span>Técnico H</span></a>
                        </li>

                        <li class="{{ Request::is('lab/get-tecnico*') ? 'active' : '' }}">
                            <a  href="{{ url('/lab/get-tecnico?elemento_id=1') }}"><i
                                    class="fa fa-flask"></i><span>Técnico Sn</span></a>
                        </li>
                        <li class="treeview"  >
                            <a href="#">
                                <i class="fa fa-cog"></i>
                                <span>Controles</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a
                                       href="{{ url('lab/calibraciones-balanzas?tipo=Calidad') }}"
                                    ><i
                                            class="fa fa-balance-scale"></i><span>Balanza 1</span></a>
                                </li>
                                <li>
                                    <a href="{{ url('lab/calibraciones-balanzas?tipo=Humedad') }}"><i
                                            class="fa fa-balance-scale"></i><span>Balanza 2</span></a>
                                </li>
                                <li>
                                    <a
                                        href="{{ url('lab/temperaturas-humedades?ambiente=Ambiente+1') }}"
                                    ><i
                                            class="fa fa-thermometer"></i><span>Ambiente 1</span></a>
                                </li>
                                <li>
                                    <a href="{{ url('lab/temperaturas-humedades?ambiente=Ambiente+2') }}"><i
                                            class="fa fa-thermometer"></i><span>Ambiente 2</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
