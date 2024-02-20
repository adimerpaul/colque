@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Ley
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'leys.store']) !!}

                        @include('leys.fields')
                    {!! Form::hidden('material_id', $id, ['class' => 'form-control']) !!}

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
