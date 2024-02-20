<!DOCTYPE html>
<html lang="en" data-bs-theme="light"> 
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
      
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
      
        <!-- preguntar dani para que sirve esto? -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- Bootstrap 5.3.0 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

        <!-- Theme style -->
        <link rel="stylesheet" href="https://scdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css">

        <!-- iCheck -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

        <style>
            .bg {
                background-image: url("{{'images/fondo3.png'}}");
                background-position: center center;
            } 

            .bg-deg {
                background-image: linear-gradient(-225deg, #042e44 33%, #025e73 52%, #26a68b 100%);
            }

            .bg-glass {
                background-color: hsla(0, 0%, 100%, 0.95) !important;
                backdrop-filter: saturate(200%) blur(25px);
            }

            .sd {
                box-shadow: 5px 5px 50px black;
            }

        </style>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <title>Colquechaca</title>
    </head>
    <body class="bg-light bg-deg">

    <!-- Section: Design Block -->
    <section class="">

    <!-- Jumbotron -->
    <div class="px-4 py-4 px-md-5 text-center text-lg-start">
        <div class="container">
            <div class="row gx-lg-5 align-items-center">
         
                <!-- lado izquierdo - inicio de sesión -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="card bg-glass sd">
                        <div class="card-body py-5 px-md-5">
                        <form method="post" action="{{ url('/login') }}">
                        @csrf
                        
                            <!-- Logo -->   
                            <div class="text-end mb-5">
                                <img class="" src="{{'logos/logo.png'}}" alt="" width="50%" height="50%">
                            </div>

                            <!-- Texto de Bienvenida -->
                            <h1 class="h1 mb-1 fw-bold text-start text-secondary">Bienvenido</h1>
                            <h5 class="mb-4 fw-normal text-start text-secondary">¡Estamos muy felices de trabajar contigo!</h5>
                            
                            <!-- Email input -->
                            <div class="form-floating mb-3 text-secondary has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" class="form-control" id="email" name="email"  placeholder="@lang('auth.email')" value="{{ old('email') }}">
                                <label for="email">Correo electrónico</label>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>

                            <!-- Password input -->
                            <div class="form-floating mb-5 text-secondary has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input type="password" class="form-control" id="password" name="password" placeholder="@lang('auth.password')" >
                                <label for="password">Contraseña</label>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>

                            <!-- Submit button original-->
                            <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-success btn-block btn-flat">Ingresar</button>
                            </div>

                            <!-- Olvidaste tu contraseña? -->
                            <div class="col mb-4 text-center">
                                <label for="password">
                                    <a href="{{route('password.request')}}"  style="text-align: center">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                </label>                            
                            </div>

                            <!-- Copyright -->
                            <div class="col text-center">
                            <p class="text-center text-muted">© Colquechaca Mining Ltda. somosDAS</p>
                            </div>  
                        </form>
                        </div>
                    </div>
                </div>    

                <!-- lado derecho - alerta cotización y texto -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                             
                    <!-- alerta cotización -->
                    @if(\App\Patrones\Fachada::tieneCotizacion())
                    <div class="text-center">
                        <div class="text-muted text-center"><small>Cotizaciones de la fecha: {{ date("d/m/Y") }}</small></div>
                        <span class="label label-success">TC Comercial: {{ \App\Patrones\Fachada::getTipoCambio()->dolar_compra }}</span>
                        <span class="label label-info">TC Oficial: {{ \App\Patrones\Fachada::getTipoCambio()->dolar_venta }}</span>
                    </div>
                    @else
                    <div class="text-center" style="margin-bottom: -20px">
                        <br>
                        <div class="alert alert-danger bg-opacity-100 alert-dismissible fade show" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Cuidado!</strong> No se ha encontrado cotizaciones para
                            la fecha {{date("d/m/Y")}}, comuníquese con el administrador.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    @endif 

                    <!-- texto -->
                    <div class="my-5">
                        <h1 class="display-2 fw-bold text-white">
                            Excelencia,<br>
                            <span class="text-white">Innovación y Honestidad</span>
                        </h1>
                        <p class="my-0" style="color: #a3d3d3">
                            <strong>Misión:</strong> Crear soluciones innovadoras basadas en tecnología y modelos de negocio disruptivos a los problemas del sector minero.<br>
                            <strong>Visión:</strong> Ser la principal empresa de la industria minero tecnológica en Latinoamérica.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Jumbotron -->
    </section>
    <!-- Section: Design Block -->

    <!-- /.login-box -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- AdminLTE App -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/js/adminlte.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    </body>
    </html>
