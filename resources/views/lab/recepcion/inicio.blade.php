@extends('lab.app')

@section('content')
    <section class="content-header">
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')

        <div class="box" style="margin-top: -50px">
            <div class="box-body">
                <div class="row" style="text-align: center">
                    <img src="{{ '/images/muestra.png'}}" style="width: 220px; height: 400px;">
                    <br>
                    <img src="{{ '/logos/logoLab.png'}}" style="width: 230px; height: 140px; margin-top: -50px">
                    <br>
                    <h1 style="font-family: 'Yu Gothic UI'"> ¿YA ERES CLIENTE COLQUECHACA?</h1><br><br>
                    <div class="form-group col-sm-12">
                        <div class="form-group col-sm-3">.</div>
                        <div class=" col-sm-6"  style="border-radius: 20px; background-color: #A8D6D4;text-align: center; cursor:pointer" role="alert"  onclick="nuevo()">
                            <br>
                            <h1 style="color: #2c6e7f; font-size: 44px" >SOY CLIENTE NUEVO</h1>
                            <br>
{{--                            <hr>--}}
{{--                            <p style="font-size: 18px">NUEVO</p>--}}
                        </div>
                        <div class="form-group col-sm-3"></div>
                    </div>
                    <div class="form-group col-sm-12">
                        <div class="form-group col-sm-3">.</div>
                        <div class="col-sm-6" role="alert" style="border-radius: 20px;background-color: #5E96A5; text-align: center; cursor:pointer;" onclick="location.href ='/recepcion-lab/create';">
                            <br>
                            <h1 style="color: white; font-size: 44px; " >SOY CLIENTE COLQUECHACA</h1>
                            <br>
{{--                            <hr>--  5E96A5 }}
{{--                            <p style="font-size: 18px">ANTIGUO</p>--}}
                        </div>
                        <div class="form-group col-sm-3">.</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        function nuevo() {
            Swal.fire({
                title: "CLIENTE NUEVO",
                text: "PASE POR VENTANILLA",
                icon: 'warning',
                type: "success",
                confirmButtonText: "ACEPTAR",
                confirmButtonColor: "#3C8DBC"
            });
        }
    </script>
@endpush



