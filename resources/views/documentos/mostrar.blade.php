@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Documentos de lote {{$formularioLiquidacion->lote}}
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">


                    @include('documentos.show')

                </div>
            </div>
        </div>
    </div>
@endsection
