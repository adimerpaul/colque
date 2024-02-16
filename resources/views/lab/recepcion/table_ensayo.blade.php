<div class="row">
    <div class=" col-sm-2">

    </div>
    <div class="table-responsive  col-sm-8">
        <table style="border: 1px solid black;" class="table table-striped" id="materiales-table">
            <thead>

            <tr>
                <th style=" border: 1px solid black;">#</th>
                <th style=" border: 1px solid black;">Elemento</th>
                <th style=" border: 1px solid black;">Lote</th>
                @if($pedido->estado==\App\Patrones\EstadoLaboratorio::Recepcionado)
                    <th style=" border: 1px solid black;"></th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($ensayos as $ensayo)
                <tr>
                    <td style=" border: 1px solid black;">{{$loop->iteration}} </td>
                    <td style=" border: 1px solid black;">{{$ensayo->elemento->nombre}}</td>
                    <td style=" border: 1px solid black;">
                        @if($ensayos->first()->recepcion->cliente_id==1)
                            <select name="lote" class="form-control" id="ensayo{{$ensayo->id}}"
                                    @change="actualizarLote({{$ensayo->id}}, $event.target.value, $event.target.selectedOptions[0].text)">
                                <option value="{{$ensayo->origen_id}}">{{$ensayo->lote}}</option>
                                    @foreach(\App\Patrones\Fachada::listarLotesLabColquechaca($ensayo->elemento_id) as $item)
                                        <option value="{{$item->id}}">{{$item->tipo. ' - '. $item->lote}} </option>
                                    @endforeach

                            </select>
                        @else
                            <input value="{{$ensayo->lote}}" class="form-control" maxlength="50"
                                   @change="actualizarLote({{$ensayo->id}}, $event.target.value)">
                        @endif
                    </td>
                    @if($pedido->estado==\App\Patrones\EstadoLaboratorio::Recepcionado)

                        <td style=" border: 1px solid black;">
                            <div class='btn-group'>
                                {!! Form::open(['route' => ['ensayos-lab.destroy', $ensayo->id], 'method' => 'delete']) !!}
                                <div class='btn-group'>

                                    {!! Form::button('&nbsp;<i class="glyphicon glyphicon-trash"></i>&nbsp;', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'title' => 'Eliminar', 'onclick' => "return confirm('¿Estás seguro de eliminar?')"]) !!}

                                </div>
                                {!! Form::close() !!}

                            </div>
                        </td>
                    @endif

                </tr>
            @endforeach

            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" style="text-align: end"><strong> TOTAL A PAGAR: </strong>{{$pedido->precio_total}} BOB
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-sm-2">
    </div>
</div>
