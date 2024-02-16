<?php

namespace App\Http\Controllers\Rrhh;

use App\Http\Controllers\Controller;
use App\Models\Rrhh\TipoHorario;
use App\Models\Personal;
use App\Models\Rrhh\Asistencia;
use App\Models\Rrhh\AsistenciaManual;
use App\Models\Rrhh\Calendario;
use App\Models\Rrhh\Feriado;
use App\Models\Rrhh\HoraExtra;
use App\Models\Rrhh\Permiso;
use App\Models\Rrhh\TipoHorarioPersonal;
use App\Models\User;
use App\Patrones\DescripcionPunto;
use App\Patrones\Fachada;
use Illuminate\Http\Request;
use Carbon\Carbon;
use function redirect;
use function view;
use Flash;
use DB;
use Faker\Provider\ar_JO\Person;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;

class AsistenciaController extends Controller

{

    public function index(Request $request)
    {


            $fechaActual = date('Y-m-d');
            $fechaInicialPredeterminada = date('Y-m-d', strtotime($fechaActual . ' - 1 months'));
            $fechaInicial = $request->fecha_i ?? $fechaInicialPredeterminada;
            $fechaFinal = $request->fecha_f ?? $fechaActual;
            $fechaFinal = date('Y-m-d', strtotime($fechaFinal));
            $personalId = $request->personal_id ?? '';
            $personalIds=Personal::pluck('id')->toArray();
            if($request->fecha_i<=$request->fecha_f){
            $resultados = Asistencia::where(function ($q) use ($personalId) {
                if ($personalId !== '' && $personalId !== '%') {
                    $q->where('personal_id', $personalId);
                }
            })
            ->whereBetween('hora_marcada', [$fechaInicial . ' 00:00:00', $fechaFinal . ' 23:59:59'])
            ->orderBy('hora_marcada', 'desc')
            ->paginate(50);
            }
            else{
                Flash::error('Introduzca de nuevo las fechas de busqueda');
                $resultados = Asistencia::where('personal_id',$personalIds)
                ->orderBy('hora_marcada', 'desc')->paginate(50);
            }
            // dd($request);



        return view('rrhh.asistencia.index', compact('resultados'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\asistencia  $asistencia
     * @return \Illuminate\Http\Response
     */
    public function show(asistencia $asistencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\asistencia  $asistencia
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\asistencia  $asistencia
     * @return \Illuminate\Http\Response
     */
    public function updateAsistencia(Request $request)
    {
        $asistencia = Asistencia::find($request->idAsistencia);

        if (!$asistencia) {
            Flash::error('La asistencia no se encontró');
            return redirect(route('asistencias.index'));}
        if (in_array($asistencia->tipo_asistencia, ['horaExtra', 'permiso', 'falta'])) {
            Flash::error('No se puede editar ' . $asistencia->tipo_asistencia);
            return redirect(route('asistencias.index'));}
        $input = $request->all();
        $asistencia->update([
            'hora_marcada' => $input['fecha'],
            'user_edicion' => auth()->user()->id,]);
        Flash::success('La asistencia se actualizó correctamente');
        return redirect(route('asistencias.index'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\asistencia  $asistencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(asistencia $asistencia)
    {
        //
    }

    public function importar(Request $request)
    {
        \DB::beginTransaction();
        try {
            $archivo = $request->file('archivo');

            if ($archivo) {
                $ruta = $archivo->getRealPath();
                $file = fopen($ruta, 'r');
                $file1 = fopen($ruta, 'r');
                $linea1 = fgetcsv($file, 0, "\t");
                $fecha = date('Y-m-d', strtotime($linea1[1]));
                $fechaRangoInicial=$fecha;
                $contador=Asistencia::where(DB::raw("CAST(hora_marcada as date)"), $fecha)
                                    ->where('tipo_asistencia','asistido')
                                    ->where('observacion',null)
                                    ->count();
                if($contador>0){
                    Flash::error('No se puede importar el archivo otra vez ');
                    return redirect(route('asistencias.index'));
                }

                while (($linea = fgetcsv($file1, 0, "\t")) !== false) {
                    //sacar los datos
                    $biometrico = trim($linea[0]);
                    // dd($biometrico);
                    $personal = Personal::whereBiometrico($biometrico)->first();
                    $fechaHora = Carbon::parse($linea[1])->format('Y-m-d H:i:s');
                    $hora_marcada = Carbon::parse($fechaHora)->format('H:i:s');
                    $hora_limite_manana = '12:00:00';
                    $hora_limite_tarde = '23:59:59';
                    $fecha = date('Y-m-d', strtotime($fechaHora));
                    if($personal!=null)
                    {
                    //Sacar biometrico una vez marcado en la mañana
                        if ($hora_marcada <= $hora_limite_manana) {
                            // Buscar si ya existe un registro de asistencia para la persona y fecha en la mañana
                            $registro = Asistencia::where(
                                'personal_id',  $personal->id)
                                ->where('tipo_asistencia','asistido')
                                ->where(DB::raw("CAST(hora_marcada as date)"), $fecha)
                                ->where(DB::raw("CAST(hora_marcada as time)"), '<', $hora_limite_manana)
                                ->first();
                            // Si no existe un registro, crear uno nuevo
                            if (!$registro) {
                                Asistencia::create([
                                    'personal_id' => $personal->id,
                                    'hora_marcada' => $fechaHora,
                                    'tipo_asistencia'=>Fachada::getTiposAsistencias()['asistencia'],
                                ]);
                            }
                        }
                        //Sacar biometrico una vez  marcado biometricio en la tarde
                        elseif ($hora_marcada > $hora_limite_manana && $hora_marcada <= $hora_limite_tarde) {
                            $registroTarde = Asistencia::where('personal_id', $personal->id)
                                ->where('tipo_asistencia','asistido')
                                ->where(DB::raw("CAST(hora_marcada as date)"), $fecha)
                                ->where(DB::raw("CAST(hora_marcada as time)"), '>', $hora_limite_manana)
                                ->first();

                            if (!$registroTarde) {
                                Asistencia::create([
                                    'personal_id' => $personal->id,
                                    'hora_marcada' => $fechaHora,
                                    'tipo_asistencia'=>Fachada::getTiposAsistencias()['asistencia'],
                                ]);
                            }
                        }
                    }
                }


                fclose($file);
                fclose($file1);
            }
            $fechaRangoFinalActual=$fecha;
            //se añade un dia mas
            $fechaRangoFinal = date('Y-m-d', strtotime($fechaRangoFinalActual . ' +1 day'));
            //Se sacan los parametros de los feriados en el rango de las fechas, sin domingos
            // Verificar si un feriado cae en lunes y se ingresan datos el martes
                $VerificacionFeriado = date('w', strtotime($fechaRangoInicial));
                if ($VerificacionFeriado == 2) {$fechaInicialFeriado = date('Y-m-d', strtotime($fechaRangoInicial . ' -1 day'));
                } else {
                    $fechaInicialFeriado = $fechaRangoInicial;
                }
            $feriadosRangoFechas = Feriado::whereBetween('fecha', [$fechaInicialFeriado, $fechaRangoFinalActual])
                        ->where(function ($query) {
                            $query->whereRaw("EXTRACT(DOW FROM fecha) <> 0"); // Excluir días domingo
                        })
                        ->get();
            //se verifica que personal cumple el parametro de fechas para tomar en cuenta la asistencia
            $personalfechas = TipoHorarioPersonal::where(function ($query) use ($fechaRangoInicial, $fechaRangoFinalActual) {
                    $query->where('fecha_inicial', '<=', $fechaRangoFinalActual)
                        ->where('fecha_fin', '>=', $fechaRangoInicial);
                })
                ->orderBy('personal_id')
                ->get();
            //Crear FALTAS
            if(!$personalfechas->isEmpty()){
            //filtar asistencias sin feriados

                $calendarioRangoFechasArray =$this->calendarioFechas($fechaRangoInicial,$fechaRangoFinalActual,$feriadosRangoFechas);
                foreach ($personalfechas as $tipoHorario){
                    if($tipoHorario->tiposHorarios->inicio_sabado){
                        $filtroAsistenciaPersonal = $this->personalDias($tipoHorario->personal_id,$fechaRangoInicial, $fechaRangoFinal);
                        $diasDiferentes=array_diff($calendarioRangoFechasArray,$filtroAsistenciaPersonal);
                    }
                    else{
                        $diasDiferentes = [];
                        $filtroAsistenciaPersonal = $this->personalDias($tipoHorario->personal_id,$fechaRangoInicial, $fechaRangoFinal);
                        $diasNoAsistidos=array_diff($calendarioRangoFechasArray,$filtroAsistenciaPersonal);
                        $diasDiferentes = array_filter($diasNoAsistidos, function($fecha) {
                            return date('w', strtotime($fecha)) != 6;});
                        }
                    if (!empty($diasDiferentes)){
                        foreach($diasDiferentes as $diaNoasistidos){
                            Asistencia::create([
                                'personal_id' => $tipoHorario->personal_id,
                                'hora_marcada' => date('Y-m-d H:i:s', strtotime(date($diaNoasistidos) . ' 00:00:00')),
                                'tipo_asistencia' => Fachada::getTiposAsistencias()['falta'],
                            ]);
                        }
                    }
                }
            }
        \DB::commit();
        Flash::success('El Archivo se subio con exito');
        return redirect(route('asistencias.index'));
        }
        catch (\Exception $e) {
        \DB::rollBack();
        Flash::error('Error al subir el archivo');
        return redirect(route('asistencias.index'));
        }

    }
    private function calendarioFechas($fechaRangoInicial,$fechaRangoFinalActual,$feriadosRangoFechas){
        $calendarioRangoFechasArray = Calendario::whereBetween('fecha', [$fechaRangoInicial, $fechaRangoFinalActual])
                                                    ->where(function ($query) {
                                                        $query->whereRaw("EXTRACT(DOW FROM fecha) <> 0");// Excluir días domingo
                                                    })
                                                    ->whereNotIn('fecha', $feriadosRangoFechas->pluck('fecha'))
                                                    ->pluck('fecha')
                                                    ->toArray();
        return array_values($calendarioRangoFechasArray);
    }
    private function personalDias($id,$fechaRangoInicial, $fechaRangoFinal)
    {
        $filtroAsistenciaPersonal = Asistencia::whereBetween('hora_marcada', [$fechaRangoInicial, $fechaRangoFinal])
                                                ->where('personal_id', $id)
                                                ->whereNotIn('tipo_asistencia', ['feriado'])
                                                ->orderBy('personal_id')
                                                ->orderBy('hora_marcada')
                                                ->selectRaw('DATE(hora_marcada) as fecha')
                                                ->get()
                                                ->pluck('fecha')
                                                ->toArray();
        return array_values($filtroAsistenciaPersonal);
    }

    //Funciones de Feriado
    /*------------------------------------ */
    //index
    public function feriados(Request $request) {
        $fechaInicial = $request->fecha_i;
        $fechaFinal = $request->fecha_f;

        if ($fechaInicial && $fechaFinal)
        {
            $feriados = Feriado::whereBetween('fecha', [$fechaInicial, $fechaFinal])
            ->orderBy('fecha','desc')->paginate(20);
        }
        else{
            $feriados = Feriado::orderBy('fecha','desc')->paginate(20);
            }
        return view('rrhh.asistencia.feriado.index',compact('feriados'));

    }
    //Crear Feriado
    public function createFeriados(){
        return view('rrhh.asistencia.feriado.create');
    }
    //crear formulario de feriado
    public function storeFeriados(Request $request){
        $input = $request->all();
        $fecha = $input["fecha"];
        $feriadoExistente = Feriado::where('fecha', $fecha)->first();
        if ($feriadoExistente) {
            Flash::info('Ya existe un feriado con la misma fecha.');
            return redirect(route('feriados.create'));
        }

        try {
            Feriado::create($input);
            $this->crearFeriadoAsistencia($fecha);
            $this->eliminarFaltasFeriado($fecha);
            Flash::success('Feriado creado satisfactoriamente ');
            return redirect(route('feriados'));

        } catch (\Exception $e) {
            Flash::error('Hubo un error al crear el feriado. Inténtalo nuevamente.');
            return redirect(route('feriados.create'));
        }
    }
    //eliminar feriado
    public function deleteFeriados($id) {
        $eliminarFecha=Feriado::find($id);
        $this->eliminarFeriadoAsistencia($eliminarFecha->fecha);
        $eliminarFecha->delete();
        Flash::error('Feriado eliminado correctamente');
        return redirect(route('feriados'));

    }
    private function eliminarFeriadoAsistencia($fecha){
        $feriadoAsistencia=Asistencia::where('hora_marcada', 'LIKE', $fecha . '%')
                                                ->where('tipo_asistencia','feriado')->get();
        foreach ($feriadoAsistencia as $feriado){
            $feriado->delete();
        }
    }
    private function crearFeriadoAsistencia($fecha){
        $horarioPersonal=Fachada::tipoHorario($fecha);
        foreach($horarioPersonal as $horario)
            {
                if ((!is_null($horario->tiposHorarios->inicio_sabado) && date('N', strtotime($fecha)) == 6) || (date('N', strtotime($fecha)) != 6)) {
                    Asistencia::create([
                        'personal_id' => $horario->personal_id,
                        'hora_marcada' => date('Y-m-d H:i:s', strtotime(date($fecha) . ' 00:00:00')),
                        'tipo_asistencia' => Fachada::getTiposAsistencias()['feriado'],
                    ]);
                }
            }
    }
    //elimina las faltas al momento de agregar feriado en la tabla Asistencia
    private function eliminarFaltasFeriado($fecha) {
        $faltaAsistencia=Asistencia::where('hora_marcada', 'LIKE', $fecha . '%')
                                                ->where('tipo_asistencia','falta')->get();

        if (!is_null($faltaAsistencia)) {
            foreach ($faltaAsistencia as $falta) {
                if (!is_null($falta)) {
                        $falta->delete();
                    }
            }
        }
    }
    /*--------------------------------------*/
    //Tipo Horario
    /*--------------------------------------*/
    public function tipoHorario()
    {
        $tiposHorarios=TipoHorario::paginate(10);
        return view('rrhh.asistencia.tipo_horario.index', compact('tiposHorarios'));

    }
    public function createTipoHorario(Request $request){
        $descripcion=$request->all();

        if($descripcion['inicio_semana'] != null || $descripcion['inicio_sabado'] != null){
            tipoHorario::create($descripcion);
            Flash::success('Descripcion creada satisfactoriamente');
            return redirect(route('tipoHorario'));
        }
        else{
            Flash::Error('Vuelva ingresa los datos');
            return redirect(route('tipoHorario'));
        }

    }
    public function editTipoHorario(Request $request){
        $tipoHorarioId = $request->input('idTipo');
        $tipoHorario = TipoHorario::find($tipoHorarioId);

        if (!$tipoHorario) {
            Flash::error('No se encontró el TipoHorario especificado.');
            return redirect(route('tipoHorario'));
        }
        $tipoHorario->fill($request->all());
        $tipoHorario->save();
        Flash::success('Descripción editada satisfactoriamente');
        return redirect(route('tipoHorario'));
    }
    /*---------------------------------------*/
    //Horario según personal
    public function tipoHorarioPersonal($id)
    {
        $personal=Personal::find($id);
        $horario = TipoHorarioPersonal::wherePersonalId($id)
               ->get();

        return view('rrhh.asistencia.tipo_horario_personal.index',compact('personal', 'horario'));
    }

    public function crearHorarioPersonal(Request $request)
    {
        $input = $request->all();
        $fechaInicial = date('Y-m-d', strtotime($request->fecha_inicial));
        $fechaFinal = date('Y-m-d', strtotime($request->fecha_fin));
        $horario = Personal::find($request->personal_id);

        $parametroHorario=TipoHorarioPersonal::where('personal_id', $horario->id)
        ->where(function ($query) use ($fechaInicial, $fechaFinal) {
            $query->whereBetween('fecha_inicial', [$fechaInicial, $fechaFinal])
                  ->orWhereBetween('fecha_fin', [$fechaInicial, $fechaFinal])
                  ->orWhere(function ($q) use ($fechaInicial, $fechaFinal) {
                      $q->where('fecha_inicial', '<', $fechaInicial)
                        ->where('fecha_fin', '>', $fechaFinal);
                  });
        })->exists();
        if ($fechaInicial < $fechaFinal && !$parametroHorario){
            TipoHorarioPersonal::create($input);
            Flash::success('Horario creado del usuario: ' . $horario->nombre_completo);
            return redirect()->route('empresas.show', '1');
        }
        else{
        Flash::error('Volver a ingresar los datos');
        return redirect()->route('tipo-horario-personal', ['id' => $horario->id]);
        }
    }
    public function editarHorarioPersonal(request $request)
    {
        $fechaFinal = TipoHorarioPersonal::find($request->idfecha);
        if ($fechaFinal) {
            $fechaFinal->fecha_fin = $request->fecha;
            $fechaFinal->update();
            Flash::success('Se editó la fecha correctamente');
            return redirect()->route('tipo-horario-personal', ['id' => $fechaFinal->personal_id]);
        } else{
            Flash::error('Volver a ingresar los datos');
            return redirect()->route('tipo-horario-personal', ['id' => $fechaFinal->personal_id]);
        }

    }
    public function eliminarHorarioPersonal($id)
    {
        $tiposHorario=TipoHorarioPersonal::find($id);
        $tiposHorario->delete();
        Flash::success('El Horario: ' . $tiposHorario->tiposHorarios->descripcion . ' fue eliminado');
        return redirect()
            ->route('tipo-horario-personal', ['id' => $tiposHorario->personal_id]);

    }

    public function mostrarAsistencia(Request $request)
    {
        $fechaActual = date('Y-m-d');
        $fechaInicialPredeterminada = date('Y-m-01', strtotime($fechaActual));
        $fechaInicial = $request->fecha_i ?? $fechaInicialPredeterminada;
        $fechaFinal = $request->fecha_f ?? $fechaActual;
        $fechaFinal = date('Y-m-d', strtotime($fechaFinal . ' + 1 days'));

        $datos=Asistencia::wherePersonalId(auth()->user()->personal->id)
        ->whereBetween('hora_marcada', [$fechaInicial, $fechaFinal])
        ->orderBy('hora_marcada', 'desc')
        ->paginate(50);
        return view('rrhh.asistencia.asistencia_personal',compact('datos'));
    }
    /*----------------------------------------*/
    //Crear Asistencia para el personal manualmente
    /*-------------------------------------*/
    public function mostrarCrearAsistencia() {
        return view('rrhh.asistencia.crear_asistencia.index');
    }

    public function crearAsistenciaManual(Request $request){
        $input=$request->all();
        if (isset($input['hora_inicio']) || $input['hora_fin'] !== null){
            $nombre=Personal::find($input['personal_id']);
                if(isset($input['ambos'])){
                    if ($input['hora_inicio'] < $input['hora_fin']){
                        $input["inicio"] = $request->fecha_inicio . ' ' . $request->hora_inicio;
                        $input["fin"] = $request->fecha_inicio . ' ' . $request->hora_fin;}
                    else{Flash::error('Reingrese los datos correctamente.');
                        return redirect()->route('permisos.create'); }
                }
                elseif(isset($input['ingreso'])){
                    $input["inicio"] = $request->fecha_inicio . ' ' . $request->hora_inicio;
                    $input["fin"] = null;
                }
                elseif(isset($input['salida'])){
                    $input["fin"] = $request->fecha_inicio . ' ' . $request->hora_fin;
                    $input["inicio"] = null;}
            AsistenciaManual::create($input);
            Flash::success('La asistencia de '. $nombre->nombre_completo.' fue Solicitado.');
            return redirect()
                ->route('home');}
        else{Flash::error('Tiene que ingresar por lo menos una hora');
            return redirect()->route('crear.asistencia');}

    }
    public function mostrarAporbarAsistencia($id){
        $asistencia=AsistenciaManual::find($id);
        if($asistencia!=null){
                if($asistencia->es_aprobado == true){
                    Flash::info('La asistencia de  '. $asistencia->personal->nombre_completo .' ya fue procesado');
                    return redirect()
                    ->route('home');}
                else{
                    return view('rrhh.asistencia.crear_asistencia.aprobacion',compact('asistencia'));}
        }
        else{
            Flash::warning('No existe el registro');
            return redirect()
            ->route('home');}
    }
    private function eliminarAsistenciaDia($asistencia)
    {
        $asistenciaAnterior = Asistencia::where('personal_id', $asistencia['personal_id'])
            ->whereDate('hora_marcada', $asistencia['inicio'])
            ->without('tipo_asistencia', 'horaExtra')
            ->get();

        if ($asistenciaAnterior->count() > 0) {
            foreach ($asistenciaAnterior as $asistenciaRegistro) {
                $asistenciaRegistro->delete();
            }}

    }
    public function aprobacionAsistencia($id) {
        \DB::beginTransaction();
        try {
                $asistencia = AsistenciaManual::find($id);
                if($asistencia->es_aprobado == false) {
                    $asistencia->es_aprobado = true;
                    $asistencia->update();
                    $asistencia->save();
                    if ($asistencia->inicio != null || $asistencia->fin != null) {

                        $data = [
                            'personal_id' => $asistencia->personal_id,
                            'tipo_asistencia' => Fachada::getTiposAsistencias()['asistencia'],
                            'observacion'=>$asistencia->motivo,
                            'user_registro' => auth()->user()->id,
                        ];

                        if ($asistencia->inicio != null) {
                            $data['hora_marcada'] = $asistencia->inicio;
                            Asistencia::create($data);
                            $this->eliminarfalta($asistencia->personal_id,$asistencia->inicio);


                        }

                        if ($asistencia->fin != null) {
                            $data['hora_marcada'] = $asistencia->fin;
                            Asistencia::create($data);
                            $this->eliminarfalta($asistencia->personal_id,$asistencia->fin);
                        }
                    }
                    \DB::commit();

                    Flash::success('La asistencia de '. $asistencia->personal->nombre_completo .' fue APROBADO');
                    return redirect()
                        ->route('home');
                }
                else{
                    \DB::commit();

                    Flash::info('La asistencia ya fue procesada');
                    return redirect()
                    ->route('home');
                }
            }
            catch (\Exception $e) {
            \DB::rollBack();
            Flash::error('Error al aprbar la asistencia');
            return redirect(route('asistencias.index'));

            }
    }
    private function eliminarfalta($personal,$fecha){
        $fecha=date('Y-m-d',strtotime($fecha));
        $dato=Asistencia::where('personal_id',$personal)
                        ->where('hora_marcada', 'LIKE', $fecha . '%')
                        ->where('tipo_asistencia', 'falta')
                        ->first();
        if (!is_null($dato)) {
            $dato->delete();
        }
    }
    public function rechazarAsitencia($id){

        $asistencia=AsistenciaManual::find($id);
        if($asistencia!=null){
            if($asistencia->es_aprobado == false) {
                $asistencia->delete();
                Flash::error('La asistencia de' . $asistencia->personal->nombre_completo .'  fue RECHAZADO');
                return redirect()
                    ->route('home');
            }
            else {Flash::info('El asistencia ya fue procesada');
                return redirect()
                ->route('home');}
        }
        else{
            Flash::warning('No existe el registro');
            return redirect()
            ->route('home');
        }
    }
    /*-------------------------------------*/
    //CALENDARIO
    /*-------------------------------------*/
    public function calendario()
    {
        $fechas = Calendario::all();
        $aniosUnicos = $fechas->pluck('fecha')->map(fn ($fecha) => date('Y', strtotime($fecha)))->unique()->toArray();
        return view('rrhh.asistencia.calendario.index',compact('aniosUnicos'));
    }
    public function calendarioCrear(Request $request)
    {
        $anio = $request->input('fecha');
        $fechas = Calendario::all();
        $aniosUnicos = $fechas->pluck('fecha')->map(fn ($fecha) => date('Y', strtotime($fecha)))->unique()->toArray();
        if ($anio != null && !in_array($anio, $aniosUnicos)){
            for ($mes = 1; $mes <= 12; $mes++) {
                $diasEnMes = Carbon::create($anio, $mes, 1)->daysInMonth;

                for ($dia = 1; $dia <= $diasEnMes; $dia++) {
                    Calendario::create([
                        'fecha' => Carbon::create($anio, $mes, $dia),
                    ]);
                }
            }
        Flash::success('Año creado correctamente');
        return redirect()->route('calendario.index');
        }
        else{
        Flash::error('Año creado con anterioridad');
        return redirect()->route('calendario.index');}
    }
    /*-------------------------------------*/


}
