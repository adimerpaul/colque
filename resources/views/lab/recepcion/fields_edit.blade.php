<div id="appEditarRecepcion2">
    <div class="col-sm-1">
    </div>
    <div class="col-sm-10">
        <div class="thumbnail">
            <img src="{{ '/logos/logoLab.png'}}" style="width: 150px; height: 75px;">

            <div class="caption">
                <h2><strong> ANÁLISIS REGISTRADOS POR EL CLIENTE</strong></h2>

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


                <div class="row">
                    <div class="table-responsive  col-sm-12">
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
                                <td style=" border: 1px solid black;"><input v-model="cantidadHumedad" type="number"
                                                                             min="1"
                                                                             max="999" oninput="maxLengthCheck(this)"
                                                                             class="form-control" maxlength="3"></td>
                                <td style=" border: 1px solid black;">@{{montoHumedad}}</td>
                            </tr>
                            <tr v-if="estanio==true">
                                <td style=" border: 1px solid black;"><i class="fa fa-flask"></i> Estaño</td>
                                <td style=" border: 1px solid black;"><input v-model="cantidadEstanio" type="number"
                                                                             min="1"
                                                                             max="999" oninput="maxLengthCheck(this)"
                                                                             class="form-control" maxlength="3"></td>
                                <td style=" border: 1px solid black;">@{{montoEstanio}}</td>
                            </tr>
                            <tr v-if="plata==true">
                                <td style=" border: 1px solid black;"><i class="fa fa-flask"></i> Plata</td>
                                <td style=" border: 1px solid black;"><input v-model="cantidadPlata" type="number"
                                                                             min="1"
                                                                             max="999" oninput="maxLengthCheck(this)"
                                                                             class="form-control" maxlength="3"></td>
                                <td style=" border: 1px solid black;">@{{montoPlata}}</td>
                            </tr>
                            </tbody>
                            <tfoot v-if="estanio==true || humedad==true || plata==true">
                            <tr>
                                <td colspan="2" style=" border: 1px solid black;"><strong> TOTAL</strong></td>
                                <td style=" border: 1px solid black;"><strong>@{{montoTotal}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2" style=" border: 1px solid black;"><strong> A CUENTA</strong></td>
                                <td style=" border: 1px solid black;"><input v-model="montoPagado" type="number" min="0"
                                                                             max="999"
                                                                             oninput="maxLengthCheck(this)"
                                                                             class="form-control" maxlength="7">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style=" border: 1px solid black;"><strong> SALDO</strong></td>
                                <td style=" border: 1px solid black;">@{{saldo}}</td>
                            </tr>
                            </tfoot>
                        </table>
                        @if($pedido->estado==\App\Patrones\EstadoLaboratorio::Recepcionado)
                            <div class="form-group col-sm-12">
                                <button class="btn btn-primary  btn-lg" @click="updateRecepcion">Guardar</button>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>


    </div>
</div>
