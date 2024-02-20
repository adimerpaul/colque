@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Documentos de cooperativa {{$cooperativa->razon_social}}
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">


                    @if(! is_null($cooperativa->url_documento))

                        <iframe src="{{ asset("documents/cooperativas/" . $cooperativa->url_documento.'?='.date('dmYHis')) }}" frameborder="0"
                                style="height: 800px; width: 100%"></iframe>

                    @endif


                </div>
            </div>
        </div>
    </div>
@endsection
