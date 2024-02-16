@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Documentos de lote {{$venta->lote}}
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">


                    @include('ventas.documentos.show_doc')

                </div>
            </div>
        </div>
    </div>
@endsection
