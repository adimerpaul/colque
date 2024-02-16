@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Contratos
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        @include('contratos.show_fields')
                        <a href="{{ route('contratos.index') }}" class="btn btn-default">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
