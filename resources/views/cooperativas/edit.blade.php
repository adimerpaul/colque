@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Productor
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div >
            <div class="box-body">
                <div class="row">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1-1"
                                   data-toggle="tab">
                                    <i class="fa fa-file"></i> Formulario </a>
                            </li>

                            <li><a href="#tab_2-2" data-toggle="tab"><i class="fa fa-upload"></i> Documentos
                                    @foreach($documentos as $documento)
                                        {!! \App\Patrones\Fachada::getColorTipoDocumento($documento->descripcion, $documento->agregado, $fecha) !!}
                                    @endforeach
                                </a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_1-1">
                                <div class="row">
                                    {!! Form::model($cooperativa, ['route' => ['cooperativas.update', $cooperativa->id], 'method' => 'patch']) !!}

                                    @include('cooperativas.fields')

                                    {!! Form::close() !!}
                                </div>
                                @if(\App\Patrones\Permiso::esAdmin() and !$cooperativa->es_aprobado)
                                    {!! Form::model($cooperativa, ['route' => ['aprobar-cliente', $cooperativa->id], 'method' => 'patch']) !!}
                                    {!! Form::hidden('tipo', \App\Patrones\TipoCliente::COOPERATIVA, ['required']) !!}

                                    {!! Form::button('Aprobar', ['type' => 'submit', 'class' => 'btn btn-success btn-lm']) !!}
                                    {!! Form::close() !!}
                                @endif
                                @if((is_null($cooperativa->user_registro_id) or $cooperativa->user_registro_id == auth()->user()->id) and !$cooperativa->es_finalizado)
                                    {!! Form::model($cooperativa, ['route' => ['finalizar-cooperativa', $cooperativa->id], 'method' => 'patch']) !!}

                                    {!! Form::button('Finalizar', ['type' => 'submit', 'class' => 'btn btn-success btn-lm', 'onclick' => "return confirm('Estas seguro de finalizar la edición')"]) !!}
                                    {!! Form::close() !!}
                                @endif
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2-2">
                                    @include('cooperativas.documentos')

                            </div>


                        </div>
                        <!-- /.tab-content -->
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
