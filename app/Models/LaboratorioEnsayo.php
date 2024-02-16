<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaboratorioEnsayo extends Model
{
    use HasFactory;

    public $table = 'laboratorio_ensayo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'codigo',
        'cliente',
        'direccion',
        'caracteristicas',
        'es_finalizado',
        'fecha_analisis',
        'fecha_finalizacion',
        'formulario_liquidacion_id',
        'sobre_sellado',
        'material_seco',
        'material_pulverizado',
        'muestra_geologica',
        'servicio',
        'preparacion',
    ];

    public $hidden = ['updated_at'];

    public $appends = [
        'elementos', 'lote', 'fecha_recepcion', 'codigo_lab', 'resultados', 'peso_humedo', 'peso_seco', 'peso_tara', 'pesos'
    ];

    public function getElementosAttribute()
    {
        $elementos = "Muestra de ";
        $labs = Laboratorio::whereLaboratorioEnsayoId($this->id)->get();

        foreach ($labs as $lab) {
            if (is_null($lab->mineral_id))
                $elementos = $elementos . "Humedad, ";
            if ($lab->mineral_id == 4)
                $elementos = $elementos . "Sn, ";
        }
        $elementos = substr(trim($elementos), 0, -1);

        return $elementos;
    }

    public function getResultadosAttribute()
    {
        $elementos = "";
        $labHumedad = LaboratorioPesoHumedad::whereLaboratorioEnsayoId($this->id)->orderByDesc('id')->first();

        if ($labHumedad) {

            try {
                $valor = ($labHumedad->peso_humedo - $labHumedad->peso_seco) / ($labHumedad->peso_humedo - $labHumedad->peso_tara) * 100;
                if($valor<0.10)
                    $valor=0.10;
            } catch (\Exception $e) {
                $valor = 0;
            }
            $elementos = $elementos . "Humedad: " . round($valor, 2) . "%";
        }
//        $labs = Laboratorio::whereLaboratorioEnsayoId($this->id)->where('valor', '<>', 0.00)->get();

//        foreach ($labs as $lab){
//            if(is_null($lab->mineral_id))
//                $elementos = $elementos . "Humedad: " . $lab->valor."%, ";
//            if($lab->mineral_id==4)
//                $elementos = $elementos . "Sn: ". $lab->valor."%, ";
//        }
//        if($labs->count()>0)
//            $elementos = substr(trim($elementos), 0, -1);
        return $elementos;
    }

    public function getLoteAttribute()
    {
        $form = FormularioLiquidacion::find($this->formulario_liquidacion_id);
        return $form->lote;
    }

    public function getPesosAttribute(){
        return sprintf("PT:%s, PH:%s, PS:%s", $this->peso_tara, $this->peso_humedo, $this->peso_seco);
    }

    public function getPesoTaraAttribute()
    {
        $peso = 0;
        $lab = LaboratorioPesoHumedad::whereLaboratorioEnsayoId($this->id)->orderByDesc('id')->first();

        if ($lab) {
            $peso = $lab->peso_tara;
        }
        return $peso;
    }

    public function getPesoHumedoAttribute()
    {
        $peso = 0;
        $lab = LaboratorioPesoHumedad::whereLaboratorioEnsayoId($this->id)->orderByDesc('id')->first();

        if ($lab) {
            $peso = $lab->peso_humedo;
        }
        return $peso;
    }

    public function getPesoSecoAttribute()
    {
        $peso = 0;
        $lab = LaboratorioPesoHumedad::whereLaboratorioEnsayoId($this->id)->orderByDesc('id')->first();

        if ($lab) {
            $peso = $lab->peso_seco;
        }
        return $peso;
    }

    public function getFechaRecepcionAttribute()
    {
        return date('d/m/Y H:i', strtotime($this->created_at));
    }

    public function getCodigoLabAttribute()
    {
        $num = str_pad($this->codigo, 5, "0", STR_PAD_LEFT);
        return $num . $this->servicio[0];
    }

    public function formularioLiquidacion()
    {
        return $this->belongsTo(FormularioLiquidacion::class);
    }


}
