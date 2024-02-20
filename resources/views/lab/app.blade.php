<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>COLQUECHACA | MINING LTDA</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.7
    -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css">
    <!-- Bootstrap 5.0.2 CSS
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/select2-boostrap.css') }}">


    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">

    <link rel="stylesheet" href="{{ asset('css/datepicker3.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .onlyprint {
            display: none;
        }

        .swal2-popup {
            font-size: 1.6rem !important;
        }
    </style>

    <style media="print">
        .onlyview {
            display: none;
        }

        .onlyprint {
            display: table;
        }

        @page {
            margin: 0.5cm 0.5cm 0.5cm 0.5cm;
        }
    </style>



    @yield('css')


    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body class="skin-blue sidebar-mini">
{{-- <p>{{ auth()->user()->rol }}</p> --}}

@if(\App\Patrones\Permiso::esLaboratorio())
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="#" class="logo">
                <b>CM</b>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">

                    <ul class="nav navbar-nav">

                        <!-- avisos para realizar la aprobacion del permiso -->
                        <?php

                        $contadorPermisos = \App\Models\Rrhh\Permiso::whereEsAprobado(false)
                            ->whereHas('personal', function ($q) {
                                $q->where('superior_id', auth()->user()->personal_id);
                            })
                            ->count();
                        ?>
                        @if($contadorPermisos)
                            <li class="nav-item avatar dropdown">

                                <a title="Permisos" href="#" class="dropdown-toggle"
                                   data-toggle="dropdown">
                            <span class="badge badge-danger ml-2"
                                  style="background-color: #E53935">{{$contadorPermisos}}</span>
                                    <i class="fa fa-bell"></i>
                                </a>

                                <div class="dropdown-menu"
                                     style="width: 200px; padding: 10px; background-color: #37474F">
                                    <br>
                                    @foreach(\App\Patrones\Fachada::listarPermisosSuperior() as $permiso)
                                        <a style="color: #ECEFF1" href="{{ route('permiso.aprobacion', [$permiso->id]) }}" class="dropdown-item waves-effect waves-light"
                                        >
                                            Permiso de <strong
                                                style="color: #90CAF9"> {{$permiso->personal->nombre_completo}}</strong></a>
                                        <hr style="height: 0.1px; background-color: #78909C; margin-top: 5px">
                                    @endforeach

                                </div>

                            </li>
                        @endif

                    <!-- User Account Menu -->
                        @include('layouts.menu_usuario')
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
    @include('lab.sidebar')
    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer" style="max-height: 100px;text-align: center">
            <strong>Copyright © {{ date('Y') }} somosDAS.</strong> All rights reserved.
        </footer>

    </div>
@else
    <nav class="navbar navbar-default navbar-static-top" style="background-color: #5E96A5">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <h3 style="color: white">
                    Colquechaca Laboratory
                </h3>
            </div>


        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endif
<script src="{{ asset('js/table2excel.js') }}"></script>

<!-- jQuery 3.1.1 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/js/adminlte.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>

<script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
{{--<script src="{{ asset('js/table2excel.js') }}"></script>--}}

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

{{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.all.min.js"></script>
<script src="https://unpkg.com/printd/printd.umd.min.js"></script>


<script type="text/javascript">
    function preVisualizarImagen(input, idImagenDestino) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(idImagenDestino).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function formarListaDeErrores(json) {
        var res = 'Errores:\n\n';
        dataError = json;
        $.each(dataError, function (i, item) {
            res += item + "\n";
        });
        return res;
    }

    function aMayuscula() {
        $(".upper").on("keypress", function () {
            $input = $(this);
            setTimeout(function () {
                $input.val($input.val().toUpperCase());
            }, 50);
        })
    }

    function cargarDatePicker() {
        $('.datepicker').datepicker({
            //useCurrent: false,
            autoclose: true,
            format: 'dd/mm/yyyy',
            locale: 'es',
            //startDate: fechaInicial,
            // endDate: fechaFinal
        });
    }

    //select2
    function iniciar_select() {
        $(".select2").select2({
            escapeMarkup: function (markup) {
                return markup;
            },
            width: null,
        });
    }


    $(document).ready(function () {
        cargarDatePicker();
        // previsualizacion de las fotos
        $("#foto_input").change(function () {
            preVisualizarImagen(this, '#img_destino');
        });

        $("#logo_input").change(function () {
            preVisualizarImagen(this, '#img_destinologo');
        });

        $("#membrete_input").change(function () {
            preVisualizarImagen(this, '#img_destino_membrete');
        });
        //selected 2
        $.fn.select2.defaults.set("theme", "bootstrap");
        iniciar_select();

        //upper
        aMayuscula();

        iniciar_note();
    });

    //note text
    function iniciar_note() {
        $('.summernote').summernote({
            lang: 'es-ES',
            height: 300,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['paragraph']],
            ]
        });
    }

    function validarFormulario(event, id) {
        event.preventDefault(); // prevent form submit
        var form = document.forms[id]; // storing the form
        Swal.fire({
            title: '¿Estas seguro?',
            text: "Se van a guardar los datos del cliente y del pesaje!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    function maxLengthCheck(object)
    {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
    }
</script>


@stack('scripts')

</body>
</html>
