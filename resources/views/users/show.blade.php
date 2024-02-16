@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Perfil de usuario
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('users.show_fields')
                    <a href="{{ route('users.editPass') }}" class="btn btn-primary">Cambiar Password</a>

                </div>
            </div>
        </div>
    </div>
@endsection
