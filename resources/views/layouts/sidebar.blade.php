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
            @include('layouts.menu')
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
