<h3 class="pull-left">Usuarios de la empresa</h3>
<h3 class="pull-right">
    <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('personals.create', ['empresa' => $empresa->id]) }}">Agregar nuevo</a>
</h3>


<div class="clearfix"></div>
@include('flash::message')
<div class="clearfix"></div>

@include('personals.table')

<div class="text-center">

</div>


