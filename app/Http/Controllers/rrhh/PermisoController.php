<?php

namespace App\Http\Controllers\rrhh;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use App\Models\Rrhh\Asistencia;
use App\Models\Rrhh\HoraExtra;
use App\Models\Rrhh\Permiso;
use Illuminate\Http\Request;
use App\Models\Rrhh\Calendario;
use App\Models\Rrhh\Feriado;
use App\Models\Rrhh\TipoHorarioPersonal;
use App\Models\Rrhh\TipoPermiso;
use App\Models\Rrhh\TipoPermisoPersonal;
use App\Models\User;
use App\Patrones\Fachada;
use Carbon\Carbon;
use DateTime;
use Flash;

use function PHPUnit\Framework\isNull;

class PermisoController extends Controller
{
    public function create()
    {
        $personal=Personal::where('id',auth()->user()->personal->id)->first();
        $nombre=$personal->nombre_completo;
        $permisos=TipoPermisoPersonal::where('personal_id',$personal->id)->get();

        return view('rrhh.permisos.create', compact('personal', 'nombre', 'permisos'));
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $personal_id=auth()->user()->personal_id;
        $tipoPermiso=TipoPermisoPersonal::where('tipo_permiso_id',$input["tipo"])->where('personal_id',$personal_id)->first();
        $input["inicio"] = $request->fecha_inicio . ' ' . $request->hora_inicio;
        $input["fin"] = $request->fecha_fin . ' ' . $request->hora_fin;
        $input["tipo"]=$tipoPermiso->tipoPermiso->descripcion;
        $control=$this->controlPermisos($input["fin"], $input["inicio"],$personal_id,$tipoPermiso->tipo_permiso_id);
        if($control==false){
            Flash::error('La cantidad de solicitada para el permiso es incorrecta o excede el límite disponible. Por favor, verifique la cantidad solicitada y asegúrese de que no supere el saldo disponible.');
            return redirect()->route('permisos.create');  
        }
        // dd($input);
        if (strtotime($input['inicio']) < strtotime($input['fin'])){
            $input["personal_id"] = auth()->user()->personal_id;
            Permiso::create($input);

            Flash::success('Permiso solicitado correctamente.');
            return redirect()
                ->route('mis-permisos');}
        else{
        Flash::error('Reingrese los datos correctamente la fecha de inicio tiene que ser mayor a la fecha final.');
            return redirect()->route('permisos.create');  }
    }
    public function permisoUsuario($id)
    {
        $permiso=Permiso::find($id);

        if($permiso!=null){
                if($permiso->es_aprobado == true){
                    Flash::info('El permiso de  '. $permiso->personal->nombre_completo .' ya fue procesado');
                    return redirect()
                    ->route('home');}
                    else{
                    return view('rrhh.permisos.permisos_pendientes.index',compact('permiso'));}
        }
        else{
            Flash::warning('No existe el registro');
            return redirect()
            ->route('home');}

    }
    public function aprobacionPermiso($id) {
        \DB::beginTransaction();
        try {
            $permiso = Permiso::find($id);
            if($permiso->es_aprobado == false) {
            $permiso->es_aprobado = true;
            $permiso->update();
            $permiso->save();
            $this->crearPermisoAsistencia($id);
            $this-> eliminarDatos($id);
            \DB::commit();
            Flash::success('El permiso de  '. $permiso->personal->nombre_completo .' fue APROBADO');
            return redirect()
                ->route('home');}
            else{
                Flash::info('El permiso ya fue procesado');
                return redirect()
                ->route('home');}
            }
        catch (\Exception $e) {
        \DB::rollBack();
        Flash::error('Error al momento de aprobar el permiso');
        return redirect()->route('permiso.aprobacion', [$permiso->id]);
        }
    }

    public function rechazarPermiso($id){

        $permiso=Permiso::find($id);
        if($permiso!=null){
            if($permiso->es_aprobado == false) {
            $permiso->delete();
            Flash::error('El permiso de' . $permiso->personal->nombre_completo .'  fue RECHAZADO');
            return redirect()
                ->route('home');
            }
            else {Flash::info('El permiso ya fue procesado');
                return redirect()
                ->route('home');}
        }
        else{
            Flash::warning('No existe el registro');
            return redirect()
            ->route('home');
        }
    }
    public function mostrarPermisos(){
        $datos=Permiso::wherePersonalId(auth()->user()->personal->id)->get();
        return view ('rrhh.permisos.registro_permisos.index',compact('datos'));
    }
    //Vista permisos generales
    public function permisosAsignadoPersonal(Request $request){
        $personal = $request->personal_id;
        if (is_null($personal))
            $personal = '%';

        $tipo = $request->tipo;
        if (is_null($tipo))
            $tipo = '%';

        $fechaInicial = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 months'));
        if (isset($request->inicio))
            $fechaInicial = $request->inicio;

        $fechaFinal = date('Y-m-d');
        if (isset($request->fin))
            $fechaFinal = $request->fin;

        $fechaFin = date('Y-m-d', strtotime($fechaFinal . ' + 1 days'));

            $permisos = Permiso::
            where(function ($q) use ($personal) {
                if ($personal !== '%') {
                    $q->where('personal_id', $personal);
                }
            })
            ->where(function ($q) use ($tipo) {
                if ($tipo !== '%') {
                    $q->where('tipo', $tipo);
                }
            })
            ->where('inicio','>=',$fechaInicial)
            ->where('fin','<=', $fechaFin)

            ->orderBy('inicio', 'desc')
            ->paginate(50);


        return view('rrhh.permisos.permisos_generales.index', compact('permisos', 'fechaInicial', 'fechaFinal'));
    }
    private function crearPermisoAsistencia($id){
        $permiso=Permiso::where('id',$id)->first();
        $usuario= User::wherePersonalId($permiso->personal->superior_id)->value('id');
        $calendario = $this->calendarioFechas($permiso->inicio, $permiso->fin,$permiso->personal_id);
        $cantidadCalendario=count($calendario);
        $hora=$this->valorHoraPermiso($id);
        if($cantidadCalendario>1){
            foreach ($calendario as $fecha){
                    Asistencia::create([
                        'personal_id' => $permiso->personal_id,
                        'hora_marcada' => date('Y-m-d H:i:s', strtotime($fecha. $hora)),
                        'tipo_asistencia' => Fachada::getTiposAsistencias()['permiso'],
                        'observacion' => $permiso->tipo,
                        'user_registro' => $usuario,
                    ]);
            }
        }
        else{
            Asistencia::create([
                'personal_id' => $permiso->personal_id,
                'hora_marcada' => date('Y-m-d H:i:s', strtotime($calendario[0] . $hora)),
                'tipo_asistencia' => Fachada::getTiposAsistencias()['permiso'],
                'observacion' => $permiso->tipo,
                'user_registro' => $usuario,
            ]);
        }
    }
    private function verificacionFeriado($fecha){
        return Feriado::where('fecha', $fecha)->exists();
    }
    private function calendarioFechas($fechaInicio, $fechaFin,$id) {
        $calendario = Calendario::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->where(function ($query) {
                $query->whereRaw("EXTRACT(DOW FROM fecha) <> 0"); // Excluir días domingo
            })
            ->pluck('fecha')
            ->toArray();
        $personalHorario=TipoHorarioPersonal::where('personal_id',$id)->latest()->first();
        if (is_null($personalHorario->tiposHorarios->inicio_sabado)) {
            $calendario = array_filter($calendario, function ($fecha) {
                return date('N', strtotime($fecha)) !== '6'; // Filtrar los sábados (día 6)
            });
        }
        $fechas = [];

        foreach ($calendario as $fecha) {
                if (!$this->verificacionFeriado($fecha)) {
                    $fechas[] = $fecha;
                }
        }
        return $fechas;

    }
    private function cantidadSabado($fechas){
        $sabados = 0;
        foreach ($fechas as $fecha) {
            // Obtener el número del día de la semana (0: Domingo, 1: Lunes, ..., 6: Sábado)
            $numeroDiaSemana = date('w', strtotime($fecha));

            // Verificar si el día de la semana es sábado (6)
            if ($numeroDiaSemana === "6") {
                $sabados=0.5+$sabados;
            }
        }
        return $sabados;
    }
    private function cantidadFeriados($fechas){
        $feriados = Feriado::all()->pluck('fecha')->toArray();
        $feriadosMedios = Feriado::where('es_turno')->pluck('fecha')->toArray();
        $fechasFeriadosMedios=count(array_intersect($feriadosMedios, $fechas));
        $cantFeriadosMid=round(($fechasFeriadosMedios/2),2);
        $fechasFeriados = count(array_intersect($feriados, $fechas));
        return $fechasFeriados-$cantFeriadosMid;
    }
    private function parametroMinutosEntreDosHorarios($horarioFinal, $horarioInicial){
        $fechaInicio = new DateTime($horarioInicial);
        $fechaFin = new DateTime($horarioFinal);
        // Calcula la diferencia en minutos
        $diferencia = $fechaFin->diff($fechaInicio);
        $diferenciaMinutos = $diferencia->days * 24 * 60 + $diferencia->h * 60 + $diferencia->i;
        return $diferenciaMinutos;
    }
    //se utiliza para ver netamente el horario no la fecha incluida
    private function parametroMinutosEntreDosHorariosSimple($horarioFinal, $horarioInicial){
        $horaInicio = strtotime(date('H:i', strtotime($horarioInicial)));
        $horaFin = strtotime(date('H:i', strtotime($horarioFinal)));
        $diferenciaMinutos = ($horaFin - $horaInicio) / 60;
        return $diferenciaMinutos;
    }
    
    private function minutosHorarioLaboral($fechaFin, $fechaInicio,$personal_id, $minutosNoLaborales = 930) {
        $cantidadSolicitada = $this->cantidadDiasPorFechas($fechaFin,$fechaInicio,$personal_id)-1;
        $fechaParametro=date('Y-m-d', strtotime($fechaInicio .'+' . $cantidadSolicitada . ' day'));
        $fechaLimite = date('Y-m-d', strtotime($fechaParametro)) . ' ' . date('H:i', strtotime($fechaFin));
        $minutos=$this->parametroMinutosEntreDosHorarios($fechaLimite,$fechaInicio)-($cantidadSolicitada*$minutosNoLaborales);
        return $minutos;
    }
    private function esMediaJornada($horarioFinal,$horarioInicial){
        $fecha=$this->parametroMinutosEntreDosHorariosSimple($horarioFinal,$horarioInicial);
        $medioJornadaManana=270;
        $medioJornadaTarde=240;
        if($fecha<=$medioJornadaManana || $fecha<=$medioJornadaTarde)
        {return 0.5;}
        else{return 0;}
    }
    private function valorHoraPermiso($id){
        $permiso=Permiso::where('id',$id)->first();
        $diferencia = abs(strtotime($permiso->inicio) - strtotime($permiso->fin));
        if ($diferencia < (8.5 * 3600)) {
            return date('H:i:s', strtotime($permiso->inicio));
        }
        else{return ' 00:00:00';}
    }
    private function eliminarDatos($id) {
        $permiso = Permiso::where('id', $id)->first();
        $calendario = $this->calendarioFechas($permiso->inicio, $permiso->fin, $permiso->personal_id);
        foreach ($calendario as $fecha) {
            $asistencia = Asistencia::where('personal_id', $permiso->personal_id)
                ->where('hora_marcada', 'LIKE', $fecha . '%')
                ->where('tipo_asistencia', 'falta')
                ->first();
            if (!is_null($asistencia)) {
                $asistencia->delete();
            }
        }
    }
    private function cantidadDiasPorFechas($horaFinal,$horaIncial,$personal_id){
            $fechaInicio = date('d/m/Y', strtotime($horaIncial));
            $fechaFin = date('d/m/Y', strtotime($horaFinal));
            $calendario = $this->calendarioFechas($fechaInicio, $fechaFin,$personal_id);
            $cantidadCalendario=count($calendario);
            $feriados=$this->cantidadFeriados($calendario);
            $mediodia=$this->esMediaJornada($horaFinal,$horaIncial);
            $cantidadDias=$cantidadCalendario-$feriados-$mediodia;
            return $cantidadDias;
    }
    //si se crea nuevo permiso revisar el caso en especifico
    private function controlPermisos($fechaFin,$fechaInicio,$personal_id,$tipo_permiso_id){
        $personalPermisos=TipoPermisoPersonal::where('personal_id',$personal_id)->where('tipo_permiso_id',$tipo_permiso_id)->first();
        $cantidadDiashabilitados=$personalPermisos->cantidad_actual;
        $control=false;
        switch($personalPermisos->tipo_permiso_id){
            case 1:
                $cantidadSolicitada=$this->parametroMinutosEntreDosHorariosSimple($fechaFin,$fechaInicio);
                if ($cantidadDiashabilitados >= $cantidadSolicitada) {
                    $control=true;}
                break;
            //Fechas estaticas anuales con un mes de vigencia
            case 5:
            case 12:
            case 19:
            case 26:
                $cantidadSolicitada = $this->cantidadDiasPorFechas($fechaFin,$fechaInicio,$personal_id);
                //Parametros de la solicitud del permiso
                $fechaInicial=date('Y-m-d',strtotime($fechaInicio));
                $fechaFinal=date('Y-m-d',strtotime($fechaFin));
                //Parametros del permiso
                $fechaInicioObjeto = DateTime::createFromFormat('m-d', $personalPermisos->tipoPermiso->fecha_inicio);
                // Coloca el año actual
                $fechaInicioObjeto->setDate(date('Y'), $fechaInicioObjeto->format('m'), $fechaInicioObjeto->format('d'));
                // Guarda la fecha original en $fechaLimite
                $fechaInicio = $fechaInicioObjeto->format('Y-m-d');
                // Aumenta un mes en la fecha de inicio
                $fechaInicioObjeto->modify('+1 month');
                // Obtiene la fecha formateada como 'Y-m-d' para $fechaInicio
                $fechaLimite = $fechaInicioObjeto->format('Y-m-d');
                if (($cantidadDiashabilitados >= $cantidadSolicitada) && ($fechaInicial==$fechaFinal) && ($fechaInicio <= $fechaInicial && $fechaInicial <= $fechaLimite)) {
                    $control = true;}
                break;
            case 6:
                $cantidadSolicitada = $this->cantidadDiasPorFechas($fechaFin,$fechaInicio,$personal_id);
                $fechaInicial=date('m-d',strtotime($fechaInicio));
                $fechaFinal=date('m-d',strtotime($fechaFin));
                $fechaCumpleanios = date('m-d', strtotime($personalPermisos->personal->fecha_nacimiento));
                $fechaLimite = date('m-d', strtotime($personalPermisos->personal->fecha_nacimiento . ' +1 month'));
                if (($cantidadDiashabilitados >= $cantidadSolicitada) &&($fechaInicial==$fechaFinal) && ($fechaCumpleanios<=$fechaInicial &&$fechaInicial<= $fechaLimite)) {
                    $control = true;}
                break;
            case 7:
            case 8:
            case 13:
                $cantidadSolicitada = $this->cantidadDiasPorFechas($fechaFin,$fechaInicio,$personal_id);
                if($personalPermisos->tipoPermiso->cantidad_dia >= $cantidadSolicitada){ 
                    $control=true;}
                break;
            case 2:                   
            case 10:
            case 20:
                $cantidadDiasSolicitados=$this->cantidadDiasPorFechas($fechaFin,$fechaInicio,$personal_id);
                if ($cantidadDiashabilitados >= $cantidadDiasSolicitados) {
                    $control=true;}
                break;
            case 23:
                $personalHorario=TipoHorarioPersonal::where('personal_id',$personal_id)->latest()->first();
                $sabadoTrabajo=$personalHorario->tiposHorarios->inicio_sabado;
                $cantidadDiasSolicitados=$this->cantidadDiasPorFechas($fechaFin,$fechaInicio,$personal_id);
                $fechaInicio = new \DateTime(date('Y-m-d H:i', strtotime($fechaInicio)));
                $fechaFin = new \DateTime(date('Y-m-d H:i', strtotime($fechaFin)));
                if (($cantidadDiashabilitados >= $cantidadDiasSolicitados) && $fechaInicio->format('N') === "6" && $fechaFin->format('N') === "6" && $sabadoTrabajo!== null) {
                    $control=true;}
                break;
            //fechas especificas    
            case 24: 
            case 27:         
                $cantidadSolicitada = $this->cantidadDiasPorFechas($fechaFin,$fechaInicio,$personal_id);
                //Parametros de la solicitud del permiso
                $fechaInicial=date('Y-m-d',strtotime($fechaInicio));
                //Parametros del permiso
                $fechaInicioPermiso = date('Y-m-d', strtotime($personalPermisos->tipoPermiso->fecha_inicio));
                $fechaLimite = date('Y-m-d', strtotime($personalPermisos->tipoPermiso->fecha_final));
                if (($cantidadDiashabilitados >= $cantidadSolicitada) && ($fechaInicioPermiso <= $fechaInicial && $fechaInicial <= $fechaLimite)) {
                    $control = true;}
                break;    
            default:
                if($personalPermisos->tipoPermiso->cantidad_dia == null && $personalPermisos->tipoPermiso->cantidad_hora == null){
                    $control=true;}
              break;      
        }
        return $control;

        
    }
    private function contarMeses($fecha){
        // Obtener la fecha actual en formato timestamp
       $fechaActual = strtotime(date('Y-m-d'));
       // Convertir la fecha pasada como parámetro a timestamp
       $fechaPasada = strtotime($fecha);
       // Calcular la diferencia en segundos
       $diferenciaSegundos = $fechaActual - $fechaPasada;
       // Calcular la diferencia en meses
       $cantidadMeses = intval($diferenciaSegundos / (60 * 60 * 24 * 30));
       return $cantidadMeses;
    }
    //si se crea nuevo permiso revisar el caso en especifico
    public function cantidadDatosPermisos($personal_id,$tipo){
        
        $cantidadTotal=0;
        $datoPermiso=TipoPermiso::where('id',$tipo)->first();
        $cantidadPermisos = Permiso::where('personal_id', $personal_id)
                                    ->where('tipo', 'LIKE', $datoPermiso->descripcion . '%')
                                    ->where('es_aprobado', true)
                                    ->get();
        if($cantidadPermisos){
            switch($datoPermiso->id){
                case 1:
                    $fecha=date('m');
                    foreach($cantidadPermisos as $cantidadPermiso){
                        $fechaPermiso=date('m',strtotime($cantidadPermiso->inicio)); 
                        if($fecha==$fechaPermiso){ 
                        $minutos = $this->parametroMinutosEntreDosHorarios($cantidadPermiso->fin,$cantidadPermiso->inicio);
                        $cantidadTotal=$minutos+$cantidadTotal;}
                    }
                    break;    
                case 2:
                case 5:    
                case 6:
                case 7:
                case 8:    
                case 12:
                case 13:    
                case 19:
                case 26:    
                    $fecha=date('Y');
                    foreach($cantidadPermisos as $cantidadPermiso){
                        $fechaPermiso=date('Y',strtotime($cantidadPermiso->inicio)); 
                        if($fecha==$fechaPermiso){ 
                        $cantidadDias=$this->cantidadDiasPorFechas($cantidadPermiso->fin,$cantidadPermiso->inicio,$personal_id);
                        $cantidadTotal=$cantidadDias+$cantidadTotal;}
                    }    
                    break;
                case 24:
                case 27:
                case 10:
                case 20:
                    foreach($cantidadPermisos as $cantidadPermiso)
                    {
                        $cantidadDias=$this->cantidadDiasPorFechas($cantidadPermiso->fin,$cantidadPermiso->inicio,$personal_id);
                        $cantidadTotal=$cantidadDias+$cantidadTotal;
                    }
                    break;
                case 23:
                    $fecha=date('m');
                    foreach($cantidadPermisos as $cantidadPermiso){
                        $fechaPermiso=date('m',strtotime($cantidadPermiso->inicio)); 
                        if($fecha==$fechaPermiso){ 
                        $cantidadDias=$this->cantidadDiasPorFechas($cantidadPermiso->fin,$cantidadPermiso->inicio,$personal_id);
                        $cantidadTotal=$cantidadDias+$cantidadTotal;}
                    }
                    break;
                default:
                    $fechaEspecifica = date('Y'); // Obtiene el año actual
                    // Filtra los permisos basándote en el año actual
                    $permisosFechaEspecifica = $cantidadPermisos->filter(function ($permiso) use ($fechaEspecifica) {
                        $inicioAño = date('Y', strtotime($permiso->inicio)); // Obtiene el año de la fecha de inicio
                        $finAño = date('Y', strtotime($permiso->fin)); // Obtiene el año de la fecha de fin
                        // Retorna true si el permiso cae dentro del año actual
                        return $inicioAño == $fechaEspecifica || $finAño == $fechaEspecifica;
                    });
                    // Obtiene el conteo de permisos para el año actual
                    $cantidadTotal = $permisosFechaEspecifica->count();
                    break;     
            }
            
            return $cantidadTotal;

        }

    }
    public function cantDatosCorrespondientes($personal_id,$tipo_permiso_id,$cantPermiso){
        $personal=Personal::where('id',$personal_id)->first();
        $permiso=TipoPermisoPersonal::where('personal_id',$personal_id)->where('tipo_permiso_id',$tipo_permiso_id)->first();
        $cantidad=0;
        if($permiso){
            switch($tipo_permiso_id)
            {   
                case 10:
                    $cantidad=(($this->contarMeses($personal->fecha_ingreso)-1)*$cantPermiso)-$personal->permiso_cantidad_inicial;
                    break;
                default:
                    if($permiso->tipoPermiso->cantidad_dia == null && $permiso->tipoPermiso->cantidad_hora == null){  
                        $cantidad=PHP_INT_MAX;}
                    else { $cantidad=$cantPermiso; }   
                    break;
            }
        }
        return $cantidad;
    }
    public function motrarCrearPermisos(){

        return view('rrhh.permisos.tipo_permiso.create');
    }
    public function crearTipoPermisosPersonal(Request $request){
        $input = $request->all();
        // Obtener el personal_id y del array
        $personal=Personal::where('id',$input['personal_id'])->first();
        $personalId = $personal->id;
        if (!isset($input['tipo_permiso_id']) || empty($input['tipo_permiso_id'])) {
            // Código para manejar el caso cuando 'tipo_permiso_id' no está presente o está vacío
            Flash::error('No se proporcionaron tipos de permisos válidos.');
            return redirect()->route('tipospermisos.create');
        }
        //comparacion de permisos.
        $permisosExistentes = TipoPermisoPersonal::where('personal_id', $personalId)
        ->whereIn('tipo_permiso_id', $input['tipo_permiso_id'])
        ->get();

        if ($permisosExistentes->isNotEmpty()) {
            // Si ya existen registros, mostrar mensaje de error
            Flash::error('Ya existe un registro con el mismo permiso para este personal.');
            return redirect()->route('tipospermisos.create');
        }
        // Crear un nuevo TipoPermisoPersonal para cada tipo_permiso_id
        foreach ($input['tipo_permiso_id'] as $tipoPermisoId) {
            TipoPermisoPersonal::create([
                'personal_id' => $personalId,
                'es_habilitado' => true,
                'tipo_permiso_id' => $tipoPermisoId,
            ]);
        }
        Flash::success('Designacion de los Permisos creado correctamente del personal: '. $personal->nombre_completo);
            return redirect()->route('tipospermisos.create');
    }
    public function crearTiposPermisos(Request $request){
        $input = $request->all();
        $input['descripcion'] = strtoupper($input['descripcion']);
        // Verificar si ambos campos están presentes y no son cero
            $cantidadDia = $input['cantidad_dia'] != 0 ? $input['cantidad_dia'] : null;
            $cantidadHora = $input['cantidad_hora'] != 0 ? $input['cantidad_hora'] : null;
            $fechaInicio = $input['fecha_inicio'] != 0 ? $input['fecha_inicio'] : null;
            $fechafinal = $input['fecha_final'] != 0 ? $input['fecha_final'] : null;

            TipoPermiso::create([
                'descripcion' => $input['descripcion'],
                'cantidad_dia' => $cantidadDia,
                'cantidad_hora' => $cantidadHora,
                'fecha_inicio' => $fechaInicio,
                'fecha_final' => $fechafinal,


            ]);
            Flash::success('Estilo de permiso creado correctamente');
            return redirect()->route('tipospermisos.create');
    }
   
}
