@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Activos Fijos
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'activos-fijos.store']) !!}


                        @include('activos.activos_fijos.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        function getProximoCodigo() {
            let id = document.getElementById('tipo_id').value;
            let url = "{{ url('proximo-codigo-activo') }}" + "/" + id;
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    document.getElementById('codigo').value = data;
                },
            });
        }

    </script>
@endpush
