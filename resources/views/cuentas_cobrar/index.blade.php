<div id="appBono">
    <h4 class="text-center">CUENTAS POR COBRAR</h4>
    <div class="pull-right">
        <a title="Dividir monto" data-toggle="modal" href="#"
           data-target="#modalCuentasPendientes"
           class="btn btn-primary btn-md">
            Pendientes de cobro
        </a>
    </div>
    <br><br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Motivo</th>
                <th>Monto (BOB)</th>
            </tr>
            </thead>
            <tbody id="tablaCuentas">
            <tr v-for="(row, index) in cuentasLista" :key="index">
                <td style="width: 80px">@{{ getDateOnly(row.created_at) }}</td>
                <td>@{{ row.motivo }}</td>
                <td style="width: 50px" class="text-right">@{{ row.monto }}</td>
                <td style="width: 80px">
                    <a v-if="formulario.es_escritura" title="Transferir" data-toggle="modal" href="#"
                       :data-txtid="row.id"
                       data-target="#modalCuenta"
                       class="btn btn-info btn-xs">
                        <i class="glyphicon glyphicon-share-alt"></i>
                    </a>

                    <a v-if="formulario.es_escritura " title="Dividir monto"
                       data-toggle="modal" href="#"
                       :data-txtid="row.id"
                       :data-txtmotivo="row.motivo"
                       :data-txtmonto="row.monto"
                       data-target="#modalDividir"
                       class="btn btn-primary btn-xs">
                        <i class="glyphicon glyphicon-minus"></i>
                    </a>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
    {!! Form::open(['route' => 'cuentas-cobrar.transferir']) !!}
    @include("cuentas_cobrar.modal_eliminar")
    {!! Form::close() !!}

    {!! Form::open(['route' => 'prestamos.dividir']) !!}
    @include("cuentas_cobrar.modal_dividir")
    {!! Form::close() !!}


    @include("cuentas_cobrar.modal_pendientes")
</div>



