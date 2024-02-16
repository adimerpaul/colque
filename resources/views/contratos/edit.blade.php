@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Contrato
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($contrato, ['route' => ['contratos.update', $contrato->id], 'method' => 'patch']) !!}

                    @include('contratos.fields')

                    {!! Form::close() !!}

{{--                    @if($contrato->producto_id=='2')--}}
{{--                        <div class="col-sm-12">--}}
{{--                            @include('contratos.base')--}}
{{--                        </div>--}}
{{--                        <div class="col-sm-12">--}}
{{--                            @include('contratos.terminos')--}}
{{--                        </div>--}}
{{--                    @endif--}}
                </div>
            </div>
        </div>
    </div>
@endsection

