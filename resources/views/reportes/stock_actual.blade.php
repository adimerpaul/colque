@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Materiales en Stock</h1>
        <br>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => 'materiales-stock', 'method' => 'get']) !!}
                            <div class="form-group col-sm-3">
                                {!! Form::label('producto_id', 'Producto: *') !!}
                                {!! Form::select('producto_id', ['%' => 'Todos'] + \App\Models\Producto::orderBy('letra')->get()->pluck('info', 'letra')->toArray(), isset($_GET['producto_id']) ? $_GET['producto_id'] : null, ['class' => 'form-control' ]) !!}
                            </div>
                            <div class="form-group col-sm-1" style="margin-top: 25px">
                                <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i>
                                    Buscar
                                </button>
                            </div>
                        {!! Form::close() !!}
                            <div class="form-group col-sm-1" style="margin-top: 25px">
                                <button type="button" class="btn btn-success"
                                        onclick="exportarAExcel()"><i
                                        class="fa fa-file-excel-o"></i>
                                    Exportar
                                </button>
                            </div>

                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-8">
                        @include('reportes.table_stock_actual')
                    </div>
                    <div class="col-sm-2">
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
{{--            {{ $materiales->appends($_GET)->links() }}--}}
        </div>
    </div>
@endsection

