<div class="form-group col-sm-6">
    {!! Form::label('producto_id', 'Producto: *') !!}
    {!! Form::select('productoId', [null => 'Seleccione...'] +  \App\Models\Producto::orderBy('letra')->get()->pluck('info', 'id')->toArray(), null, ['class' => 'form-control', 'required', 'v-model'=> 'productoId' ]) !!}
</div>



<div class="form-group col-sm-6" v-if="productoId==1 || productoId==2">
    {!! Form::label('transporte', 'Transporte: *') !!}
    {!! Form::number('transporte', null, ['class' => 'form-control', 'v-model' =>'transporte', 'required', 'min' => 0, 'step'=>'0.01']) !!}
</div>

<div class="form-group col-sm-6" v-if="productoId==1 || productoId==2 || productoId==6">
    {!! Form::label('leyAg', 'Ley Ag DM: *') !!}
    {!! Form::number('leyAg', null, ['class' => 'form-control', 'v-model'=> 'leyAg', 'required', 'min'=>0, 'step'=>'0.01']) !!}
</div>

<div class="form-group col-sm-6" v-if="productoId==1">
    {!! Form::label('leyZn', 'Ley Zn %: *') !!}
    {!! Form::number('leyZn', null, ['class' => 'form-control', 'v-model' =>'leyZn', 'required', 'min' => 0, 'step'=>'0.01']) !!}
</div>

<div class="form-group col-sm-6" v-if="productoId==2">
    {!! Form::label('leyPb', 'Ley Pb %: *') !!}
    {!! Form::number('leyPb', null, ['class' => 'form-control', 'v-model' =>'leyPb', 'required', 'min' => 0, 'step'=>'0.01']) !!}
</div>

<div class="form-group col-sm-6" v-if="productoId==4">
    {!! Form::label('leySn', 'Ley Sn %: *') !!}
    {!! Form::number('leySn', null, ['class' => 'form-control', 'v-model' =>'leySn', 'required', 'min' => 0, 'step'=>'0.01']) !!}
</div>

<div class="form-group col-sm-6" v-if="productoId==7">
    {!! Form::label('leyCu', 'Ley Cu %: *') !!}
    {!! Form::number('leyCu', null, ['class' => 'form-control', 'v-model' =>'leyCu', 'required', 'min' => 0, 'step'=>'0.01']) !!}
</div>

<div class="form-group col-sm-6" v-if="productoId==5">
    {!! Form::label('leySb', 'Ley Sb %: *') !!}
    {!! Form::number('leySb', null, ['class' => 'form-control', 'v-model' =>'leySb', 'required', 'min' => 0, 'step'=>'0.01']) !!}
</div>

<div class="form-group col-sm-6" v-if="productoId==1 || productoId==2 || productoId==6 || productoId==7 || productoId==5">
    {!! Form::label('tipo_material', 'Tipo: *') !!}
    {!! Form::select('tipo_material',  \App\Patrones\Fachada::getTiposMaterial(), null, ['class' => 'form-control','id' => 'tipo_material', 'v-model' =>'tipo_material', 'required']) !!}
</div>


