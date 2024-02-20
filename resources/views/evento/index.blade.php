@extends('layouts.app')

@section('content')
<section class="content-header">
    <h1 class="pull-left">Eventos</h1>
    <br>
</section>
<div class="content" >
    <div class="clearfix"></div>
    <div class="box box-primary">
        <div class="box-body">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                Crear Vento Significativo
            </button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Opciones</th>
                        <th>Codigo Evento</th>
                        <th>Tipo Evento</th>
                        <th>Fecha_inicio</th>
                        <th>Fecha_fin</th>
                        <th>Cuf</th>
                    </tr>
                </thead>
                <tbody id="eventos">
                </tbody>
            </table>
            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Crear Evento Significativo</h4>
                        </div>
                        <div class="modal-body">
                            <form role="form" id="form">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="cuf">Cuf</label>
                                        <select class="form-control" id="cuf"></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="tipo">Tipo Evento</label>
                                        <select class="form-control" id="tipo">
                                            <option value="Compra Venta">Compra Venta</option>
                                            <option value="Mineral">Mineral</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="motivo">Motivo</label>
                                        <select class="form-control" id="motivo"></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_inicio">Fecha Inicio</label>
                                        <input class="form-control" id="fecha_inicio" placeholder="Fecha Inicio">
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_fin">Fecha Fin</label>
                                        <input class="form-control" id="fecha_fin" placeholder="Fecha Fin">
                                    </div>
                                </div>
                                <div class="box-footer text-right">
                                    <button type="submit" class="btn btn-success">Crear</button>
                                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-result">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Result Evento Significativo</h4>
                        </div>
                        <div class="modal-body">
                            <pre id="result"></pre>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    window.onload = function() {
        $('#fecha_inicio').val(moment().format('YYYY-MM-DD HH:mm:ss'))
        $('#fecha_fin').val(moment().add(2, 'seconds').format('YYYY-MM-DD HH:mm:ss'));
        getEventos()
        getCufs()
        getMotivoEvento()
        $('#form').submit(function (e) {
            e.preventDefault()
            let data = {
                cuf_id: $('#cuf').val(),
                fecha_inicio: $('#fecha_inicio').val(),
                fecha_fin: $('#fecha_fin').val(),
                motivo: $('#motivo').val(),
                tipo: $('#tipo').val(),
                _token: '{{ csrf_token() }}'
            }
            $.ajax({
                url: '/createEvento',
                type: 'POST',
                data: data,
                success: function (response) {
                    console.log(response)
                    $('#modal-default').modal('hide')
                    getEventos()
                }
            })
        })
        function getMotivoEvento() {
            $.ajax({
                url: '/motivoEvento',
                type: 'GET',
                success: function (response) {
                    $('#motivo').empty()
                    response.forEach(motivo => {
                        $('#motivo').append(`<option value="${motivo.codigo}">${motivo.codigo} ${motivo.descripcion}</option>`)
                    })
                }
            })
        }

        $(document).on('click', '.envioPaquetes', function() {
            var id = $(this).data('id');
            envioPaquetes(id);
        });
        $(document).on('click', '.verificar', function() {
            var id = $(this).data('id');
            verificar(id);
        });
        function verificar(id) {
            $.ajax({
                url: '/verificar',
                type: 'POST',
                data: {id: id, _token: '{{ csrf_token() }}'},
                success: function (response) {
                    console.log(response);
                    // getEventos();
                    // modal-result
                    $('#result').text(JSON.stringify(response, null, 2))
                    $('#modal-result').modal('show')
                }
            });
        }


        function envioPaquetes(id) {
            console.log(id);
            console.log('xxx');
            $.ajax({
                url: '/envioPaquetes',
                type: 'POST',
                data: {id: id, _token: '{{ csrf_token() }}'},
                success: function (response) {
                    // console.log('success');
                    // getEventos();
                },
                // finally
                complete: function (response) {
                    // console.log('complete');
                    getEventos();
                }
            });
        }
        function getEventos() {
            $.ajax({
                url: '/eventos',
                type: 'GET',
                success: function (response) {
                    $('#eventos').empty()
                    let cont = 0
                    response.forEach(evento => {
                        cont++
                        $('#eventos').append(`
                            <tr>
                                <td>${cont}</td>
                                <td>
                                    <button type="button" class="btn btn-primary envioPaquetes" data-id="${evento.id}">Enviar</button>
                                    <button type="button" class="btn btn-info verificar" data-id="${evento.id}">Verificar</button>
                                </td>
                                <td>${evento.codigo_recepcion}</td>
                                <td>${evento.tipo}</td>
                                <td>${evento.fecha_inicio}</td>
                                <td>${evento.fecha_fin}</td>
                                <td>${evento.cufd}</td>
                            </tr>
                        `)
                    })
                }
            })
        }
        function getCufs() {
            $.ajax({
                url: '/cufs',
                type: 'GET',
                success: function (response) {
                    $('#cuf').empty()
                    response.forEach(cuf => {
                        let name = cuf.created_at
                        name = name.substring(0, 10)
                        $('#cuf').append(`<option value="${cuf.id}">${name}</option>`)
                    })
                }
            })
        }
    }
</script>
