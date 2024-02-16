<li class="dropdown user user-menu">
    <!-- Menu Toggle Button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" >
        <!-- The user image in the navbar-->
        <img
            src="https://ui-avatars.com/api/?name={{ auth()->user()->personal->nombre_completo }}"
            class="user-image" alt="User Image"/>
        <!-- hidden-xs hides the username on small devices so only the image appears. -->
        <span class="hidden-xs">{{ Auth::user()->personal->nombre_completo }}</span>
    </a>
    <ul class="dropdown-menu ">
        <!-- The user image in the menu -->
        <li class="user-header">
            <img
                src="https://ui-avatars.com/api/?name={{ auth()->user()->personal->nombre_completo }}"
                class="img-circle" alt="User Image"/>
            <p>
                {{ Auth::user()->personal->nombre_completo }}
                <small>{{ \Illuminate\Support\Facades\Auth::user()->rol  }}</small>
            </p>
        </li>
        <!-- Menu Footer-->
        <!-- Datos del perfil-->

        <li>
            <a href="{{ route('users.perfil') }}" style="background-color: #28353a; color: #DDDDDD;">
                <i class="fa fa-address-card-o" aria-hidden="true" style="float: left;"></i>
                <span style="display: inline-block;">@lang('auth.app.profile')</span>
            </a>
        </li>
        <!-- Asistencia-->
        <li>
            <a href="{{ route('mis-asistencias') }}"style="background-color: #28353a; color: #DDDDDD;">
                <i class="fa fa-hourglass" aria-hidden="true" style="float: left;"></i>
                <span style="display: inline-block;">Asistencia</span>
            </a>
        </li>
        <!-- Hora Extra -->
        @if (26 === auth()->user()->personal->id)
        <li>
            <a href="{{ route('mis-horas-extra') }}"style="background-color: #28353a; color: #DDDDDD;">
                <i class="fa fa-list" aria-hidden="true" style="float: left;"></i>
                <span style="display: inline-block;">Hora Extra</span>
            </a>
        </li>
        @endif
        <!-- Permisos-->
        <li>
            <a href="{{ route('mis-permisos') }}" style="background-color: #28353a; color: #DDDDDD;">
                <i class="fa fa-check-circle" aria-hidden="true" style="float: left;"></i>
                <span style="display: inline-block;">Permisos</span>
            </a>
        </li>
        <!-- Cerrar cesion-->
        <li>
            <a href="{{ url('/logout') }}"  style="background-color: #28353a; color: #DDDDDD;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out" aria-hidden="true" style="float: left;"></i>
                <span style="display: inline-block;">Cerrar Sesión</span>

            </a>
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>

</li>
