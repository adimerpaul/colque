<div class="col-sm-12">
    <div class="form-group col-sm-12" style="background-color: #BBDEFB">
        {!! Form::label('cliente', 'Cliente: *', ['style' => 'margin-top:10px']) !!}
        {!! Form::select('cliente_id', \App\Patrones\Fachada::listarCLientesLaboratorio(), null, ['class' => 'form-control select2', 'id' => 'cliente_id', 'required']) !!}
        <br>
    </div>
{{--    <div class="form-group col-sm-10" style="margin-top: 25px">--}}
{{--        {!! Form::text('txtBuscar', null, ['class' => 'form-control', 'v-model' =>'buscador',--}}

{{--        'placeholder'=>'Buscar por Nombre, Nit']) !!}--}}

{{--    </div>--}}

{{--    <div class="form-group col-sm-1" style="margin-top: 25px">--}}
{{--        <button type="button" class="btn btn-default"  @click="buscarCliente()">&nbsp;<i class="glyphicon glyphicon-search"></i>--}}

{{--        </button>--}}
{{--    </div>--}}


<!-- Nombre Field -->


</div>

{{--<div class="col-sm-6">--}}
{{--    <form class="form">--}}

{{--            <tr>--}}
{{--                <td><label class="control-label" style="margin-top: 7px; color:white">Nombre:</label></td>--}}
{{--                <td>--}}
{{--                    <input style="border: 0; background: -webkit-linear-gradient(to right, #29454e, #2e6b7f); background: linear-gradient(to right, #40849b, #79d6f5); color: white;" readonly class="form-control" id="nombreCliente">--}}
{{--                </td>--}}


{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td><label class="control-label" style="margin-top: 7px; color:white">Nit:</label></td>--}}
{{--                <td><input  style="border: 0; background: -webkit-linear-gradient(to right, #29454e, #2e6b7f); background: linear-gradient(to right, #40849b, #79d6f5); color: white;" readonly class="form-control" id="nitCliente" ></td>--}}
{{--            </tr>--}}

{{--    </form>--}}
{{--</div>--}}
<div class="col-sm-1">
</div>
<div class="col-sm-10">
    <div class="thumbnail">
        <img src="{{ '/logos/logoLab.png'}}" style="width: 150px; height: 75px;">

        <div class="caption">
            <h2><strong  id="txtBienvenida"> HOLA, ¿QUÉ HARÁS ANALIZAR HOY?</strong></h2>
            <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget
                metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>

            <div class="form-group col-sm-4" style="margin-top: 20px">
                {!! Form::label('humedad', 'Humedad: *') !!}

                <div class="cl-toggle-switch">

                    <label class="cl-switch" for="myonoffswitch">
                        <input type="checkbox" name="onoffswitch" class="cl-switch-checkbox" id="myonoffswitch"
                               :checked="humedad" v-model="humedad">
                        <span></span>

                    </label>
                </div>
            </div>

            <div class="form-group col-sm-4" style="margin-top: 20px">
                {!! Form::label('estanio', 'Estaño: *') !!}

                <div class="cl-toggle-switch">
                    <label class="cl-switch" for="onoffswitchSn">
                        <input type="checkbox" name="onoffswitch" class="cl-switch-checkbox" id="onoffswitchSn"
                               :checked="estanio" v-model="estanio">
                        <span></span>
                    </label>
                </div>
            </div>

            <div class="form-group col-sm-4" style="margin-top: 20px">
                {!! Form::label('plata', 'Plata: *') !!}

                <div class="cl-toggle-switch">
                    <label class="cl-switch" for="onoffswitchAg">
                        <input type="checkbox" name="onoffswitch" class="cl-switch-checkbox" id="onoffswitchAg"
                               :checked="plata" v-model="plata">
                        <span></span>
                    </label>
                </div>
            </div>


            <table style="border: 1px solid black;" class="table table-striped" id="materiales-table">
                <thead>

                <tr>
                    <th style=" border: 1px solid black;">Elemento</th>
                    <th style=" border: 1px solid black;">Cantidad de Muestras</th>
                    <th style=" border: 1px solid black;">Precio</th>
                </tr>
                </thead>
                <tbody>

                <tr v-if="humedad==true">
                    <td style=" border: 1px solid black;"><i class="fa fa-tint"></i> Humedad</td>
                    <td style=" border: 1px solid black;"><input onkeypress="return onlyNumberKey(event)"
                                                                 style="background-color: #BBDEFB"
                                                                 v-model="cantidadHumedad"
                                                                 type="number" min="1" max="999"
                                                                 oninput="maxLengthCheck(this)"
                                                                 class="form-control" maxlength="3"></td>
                    <td style=" border: 1px solid black;">@{{montoHumedad}}</td>
                </tr>
                <tr v-if="estanio==true">
                    <td style=" border: 1px solid black;"><i class="fa fa-flask"></i> Estaño</td>
                    <td style=" border: 1px solid black;"><input onkeypress="return onlyNumberKey(event)"
                                                                 style="background-color: #BBDEFB"
                                                                 v-model="cantidadEstanio"
                                                                 type="number" min="1" max="999"
                                                                 oninput="maxLengthCheck(this)"
                                                                 class="form-control" maxlength="3"></td>
                    <td style=" border: 1px solid black;">@{{montoEstanio}}</td>
                </tr>
                <tr v-if="plata==true">
                    <td style=" border: 1px solid black;"><i class="fa fa-flask"></i> Plata</td>
                    <td style=" border: 1px solid black;"><input onkeypress="return onlyNumberKey(event)"
                                                                 style="background-color: #BBDEFB"
                                                                 v-model="cantidadPlata"
                                                                 type="number" min="1" max="999"
                                                                 oninput="maxLengthCheck(this)"
                                                                 class="form-control" maxlength="3"></td>
                    <td style=" border: 1px solid black;">@{{montoPlata}}</td>
                </tr>
                </tbody>
                <tbody v-if="estanio==true || humedad==true  || plata==true">
                <tr>
                    <td colspan="2" style=" border: 1px solid black;"><strong> TOTAL</strong></td>
                    <td style=" border: 1px solid black;"><strong>@{{montoTotal}}</strong></td>
                </tr>
                <tr>
                    <td colspan="2" style=" border: 1px solid black;"><strong> A CUENTA</strong></td>
                    <td style=" border: 1px solid black;"><input onkeypress="return onlyNumberKey(event)"
                                                                 style="background-color: #BBDEFB" v-model="montoPagado"
                                                                 type="number" min="0" max="999"
                                                                 oninput="maxLengthCheck(this)"
                                                                 class="form-control" maxlength="7"></td>
                </tr>
                <tr>
                    <td colspan="2" style=" border: 1px solid black;"><strong> SALDO</strong></td>
                    <td style=" border: 1px solid black;">@{{saldo}}</td>
                </tr>
                </tbody>
            </table>

            <p><button @click="saveRecepcion" class="btn btn-primary btn-lg" role="button" id="saveRecepcion">Guardar</button>
                <a onclick="history.back()" class="btn btn-default btn-lg" role="button">Cancelar</a></p>

        </div>
    </div>
</div>
<br>
<div class="col-sm-1">
</div>

<div class="table-responsive  col-sm-3">
</div>
