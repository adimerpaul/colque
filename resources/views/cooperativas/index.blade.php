@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Productores (Cooperativas | Empresas)</h1>
        <h1 class="pull-right">


                @if(\App\Patrones\Permiso::esAdmin())
                    <button style="margin-top: -10px;margin-bottom: 5px" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        Listas para bonos <i style="margin-top: 3px" class="fa fa-angle-down pull-right"></i>
                    </button>
                    <div class="dropdown-menu">
                        <div class="dropdown-divider"  style="margin-top:4px;"></div>

                        <a class='dropdown-item'
                           href="{{ route('clientes-registrados') }}"
                        >
                            <i class="fa fa-user"></i>
                            Registros Clientes
                        </a>

                        <div class="dropdown-divider" style="margin-top:4px;"></div>

                        <a class='dropdown-item'
                           href="{{ route('clientes-editados') }}"
                        >
                            <i class="fa fa-edit"></i>
                            Edición Clientes
                        </a>

                        <div class="dropdown-divider" style="margin-top:4px;"></div>
                        <a class='dropdown-item'
                           href="{{ route('productores-finalizados') }}"
                        >
                            <i class="fa fa-check"></i>
                            Productores
                        </a>

                        <div class="dropdown-divider"></div>
                    </div>
                @endif

                    @if(\App\Patrones\Permiso::esComercial())
                        <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
                           href="{{ route('cooperativas.create') }}">Agregar nuevo</a>
                    @endif
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'cooperativas.index', 'method'=>'get']) !!}

                        <div class="form-group col-sm-6">
                            {!! Form::label('txtBuscar', 'Buscar por:') !!}
                            {!! Form::text('txtBuscar', isset($_GET['txtBuscar']) ? $_GET['txtBuscar'] : null, ['class' => 'form-control', 'placeholder'=>'Nro Nim, Razón Social']) !!}
                        </div>

                        <div class="form-group col-sm-2" style="margin-top: 25px">
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                Buscar
                            </button>
                        </div>
                        @if(\App\Patrones\Permiso::esComercial() OR \App\Patrones\Permiso::esInvitado())
                            <div class="form-group col-sm-2 pull-right" style="margin-top: 25px">
                                <a data-toggle="modal" data-target="#modalBusquedaClientes"
                                   class="btn btn-primary pull-right">Buscar por clientes</a>
                            </div>
                        @endif
                        {!! Form::close() !!}

                    </div>
                </div>
                @include('cooperativas.table')
            </div>
        </div>
        <div class="text-center">
            {{ $cooperativas->appends($_GET)->links()  }}
        </div>
    </div>
    <div id="appBusquedaClientes">
        @include("cooperativas.buscador")
        @include("cooperativas.modal_laboratorio")
    </div>
@endsection

@push('scripts')

    <script>
        $('#modalAnalisisLaboratorio').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id = button.data('txtid')

            var modal = $(this)
            modal.find('.modal-body #idCooperativa').val(id);
        })

    </script>

@endpush


