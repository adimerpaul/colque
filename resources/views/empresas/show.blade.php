@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Detalles de la empresa: {{ $empresa->razon_social }}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <div class="col-sm-2" style="border-right: 1px solid #A5A5A5">
                        @include('empresas.show_fields')
                        <a href="{{ route('empresas.index') }}" class="btn btn-default">Volver</a>
                    </div>
                    <div class="col-sm-10">
                        @include('personals.index', ['personals' => $empresa->personals])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
