<div style="background-color: #f7f7f7; padding-top:12px; margin-left: -9px; margin-right: -9px; margin-top: -9px">

    <div class="row" >
        <div class="col-sm-12">

            <div class="col-sm-3">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-dollar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ventas</span>
                        <span class="info-box-number">PNS: {{number_format($ventasPesoSeco, 2)}}</span>
                        <span class="info-box-number">BOB: {{number_format($ventasNetoVenta, 2)}}</span>
                    </div><!-- /.info-box-content -->
                </div>
            </div><!-- /.-->
            <div class="col-sm-3">
                <div class="info-box">
                    <span class="info-box-icon bg-red elevation-1"><i class="fa fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Compras</span>
                        <span class="info-box-number">PNS: {{number_format($comprasPesoSeco, 2)}}</span>
                        <span class="info-box-number">BOB: {{number_format($comprasNetoVenta, 2)}}</span>
                    </div><!-- /.info-box-content -->
                </div>
            </div><!-- /.info-box -->
            <div class="col-sm-3">
                <div class="info-box">
                    <span class="info-box-icon bg-green elevation-1"><i class="fa fa-calculator"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Utilidad bruta</span>
                        <span class="info-box-number">93,139</span>
                    </div><!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-sm-3">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Inventario a la fecha</span>
                        <span class="info-box-number">PNS: {{number_format($inventarioPesoSeco, 2)}}</span>
                        <span class="info-box-number">BOB: {{number_format($inventarioNetoVenta, 2)}}</span>
                    </div><!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-sm-3">
                <div class="info-box">

                    <span class="info-box-icon " style="background-color: #00C0EF"><i class="fa fa-users" style="color: white"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Nuevos clientes quincena</span>
                        <span class="info-box-number">{{$clientes}}</span>
                    </div><!-- /.info-box-content -->
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div id="chartNetoYSeco"></div>
                                {!! $chartNetoYSeco !!}
                                </div>
                                <!-- /.col -->
                                <div class="col-md-4">
                                    <p class="text-center">
                                        <strong>OBJETIVOS TRAZADOS</strong>
                                    </p>

                                    <div class="progress-group">
                                        Compras Anuales (PNS)
                                        <span class="float-right"><b>{{$comprasMetaPesoSeco}}</b>/{{$metas['comprasPesoSeco'] }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-blue" style="width: {{$porcentajesMetas['comprasPesoSeco'] }}%"></div>
                                        </div>
                                    </div>
                                    <!-- /.progress-group -->

                                    <div class="progress-group">
                                        Compras Anuales (BOB)
                                        <span class="float-right"><b>{{$comprasMetaNetoVenta}}</b>/{{$metas['comprasNetoVenta'] }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-red" style="width: {{$porcentajesMetas['comprasNetoVenta'] }}%"></div>
                                        </div>
                                    </div>

                                    <!-- /.progress-group -->
                                    <div class="progress-group">
                                        Compras SN (BOB)
                                        <span class="float-right"><b>{{$comprasMetaSn}}</b>/{{$metas['comprasSn'] }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-green" style="width: {{$porcentajesMetas['comprasSn'] }}%"></div>
                                        </div>
                                    </div>

                                    <!-- /.progress-group -->
                                    <div class="progress-group">
                                        Compras ZN - AG (BOB)
                                        <span class="float-right"><b>{{$comprasMetaZnAg}}</b>/{{$metas['comprasZnAg'] }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-yellow" style="width: {{$porcentajesMetas['comprasZnAg'] }}%"></div>
                                        </div>
                                    </div>

                                    <div class="progress-group">
                                        Compras PB - AG (BOB)
                                        <span class="float-right"><b>{{$comprasMetaPbAg}}</b>/{{$metas['comprasPbAg'] }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar" style="width: {{$porcentajesMetas['comprasPbAg'] }}%; background-color: #00C0EF" ></div>
                                        </div>
                                    </div>

                                    <div class="progress-group">
                                        Número de ventas mensuales
                                        <span class="float-right"><b>{{$ventasMeta}}</b>/{{$metas['ventasMensuales'] }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-purple" style="width: {{$porcentajesMetas['ventasMensuales'] }}%"></div>
                                        </div>
                                    </div>
                                    <!-- /.progress-group -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- ./card-body -->

                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Main row -->

            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>

</div>
