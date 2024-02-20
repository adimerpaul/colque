<?php

namespace App\Http\Controllers\rrhh;

use App\Http\Controllers\Controller;
use App\Models\Rrhh\Asistencia;
use App\Models\Rrhh\HoraExtra;
use Carbon\Carbon;
use App\Patrones\Fachada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Flash;
use function redirect;
use function view;


class HoraExtraController extends Controller
{
    public function index(Request $request)
    {   
        $fechaInicial = $request->inicio ?? ''; 
        $fechaFinal = $request->fin ?? ''; 
        $personal = $request->personal_id ?? ''; 
        if ($fechaInicial || $fechaFinal || $personal !== '') {
            $horasExtra = HoraExtra::
            where(function ($q) use ($personal) {
                if ($personal !== '' and $personal !== '%') {
                    $q->where('personal_id', $personal);
                }
            })
            ->where('inicio','>=',$fechaInicial)
            ->where('fin','<=', Carbon::parse($fechaFinal)->addDay())
            ->where('es_aprobado',true)
            ->orderBy('inicio', 'desc')
            ->paginate(50);
        }
        else {

            $horasExtra = HoraExtra::where('es_aprobado',true)
            ->orderBy('inicio', 'desc')->paginate(50);

        }

        return view('rrhh.permisos.horas_extra.index',compact('horasExtra'));
    }
    public function create()
    {   
        return view('rrhh.permisos.horas_extra.create');
    }
    public function store(Request $request)
    {
        $input=$request->all();
        if($input['hora_inicio'] < $input['hora_fin']){
        $input["inicio"] = $request->fecha_inicio . ' ' . $request->hora_inicio;
        $input["fin"] = $request->fecha_fin . ' ' . $request->hora_fin;
        HoraExtra::create($input);
        Flash::success('Hora extra solicitada correctamente.');
        return redirect()->route('horas-extras.index');}
        else{
            Flash::error('Ingrese y verifique los datos nuevamente.');
            return redirect()->route('horas-extras.create');}
    }
    public function show($id)
    {   
        $horaExtra=HoraExtra::find($id);
        if($horaExtra!=null){
                if($horaExtra->es_aprobado == true){
                    Flash::info('La hora extra de  '. $horaExtra->personal->nombre_completo .' ya fue procesado');
                    return redirect()   
                    ->route('home');}
                else{
                    return view('rrhh.permisos.horas_extra.aprobar',compact('horaExtra'));}
        }
        else{ 
            Flash::warning('No existe el registro');
            return redirect()
            ->route('home');}
    }
    public function edit($id)
    {   
        $horasExtra=HoraExtra::find($id);
        return view('rrhh.permisos.horas_extra.edit',compact('horasExtra'));
    }
    public function update(Request $request,$id)
    {
        $editar=HoraExtra::find($id);
        $horaExtraInicial=Asistencia::where('personal_id',$editar->personal_id)
                               ->where('hora_marcada',$editar->inicio)
                               ->where('tipo_asistencia','horaExtra')
                               ->delete();
        $horaExtraFinal=Asistencia::where('personal_id',$editar->personal_id)
                            ->where('hora_marcada',$editar->fin)
                            ->where('tipo_asistencia','horaExtra')
                            ->delete();
        $input=$request->all();
        $editar['es_aprobado']=false;
        $editar->update($input);
        $editar->save();
        Flash::success('Datos solicitados para su edicion correctamente.');
        return redirect(route('horas-extras.index'));
        
    }
    public function destroy($id)
    {
        $horaExtra=HoraExtra::find($id);
        if($horaExtra!=null){
            if($horaExtra->es_aprobado == false) {
            $horaExtra->delete();
            Flash::error('La hora extra de' . $horaExtra->personal->nombre_completo .'  fue RECHAZADO');
            return redirect()
                ->route('home');
            }
            else {Flash::info('El hora extra ya fue procesado');
                return redirect()
                ->route('home');}
        }
        else{ 
            Flash::warning('No existe el registro');
            return redirect()
            ->route('home');
        }
    }

    public function aprobacionHoraExtra($id) {
        $horaExtra = HoraExtra::find($id);
        if($horaExtra->es_aprobado == false) {
        $horaExtra->es_aprobado = true; 
        $horaExtra->update();
        $horaExtra->save();
        Asistencia::create([
            'personal_id' => $horaExtra->personal_id,
            'hora_marcada' => $horaExtra->inicio,
            'tipo_asistencia' => Fachada::getTiposAsistencias()['horaExtra'],
        ]);
        Asistencia::create([
            'personal_id' => $horaExtra->personal_id,
            'hora_marcada' => $horaExtra->fin,
            'tipo_asistencia' => Fachada::getTiposAsistencias()['horaExtra'],
            'observacion'=>$horaExtra->descripcion,
            'user_registro'=>auth()->user()->id,

        ]);

        Flash::success('La asistencia de '. $horaExtra->personal->nombre_completo .' fue APROBADO');
        return redirect()
            ->route('home');}
        else{
            Flash::info('La asistencia ya fue procesada');
            return redirect()
            ->route('home');}

    }

    public function miHoraExtra(Request $request)
    {   
        if (26 === auth()->user()->personal->id) {

            $fechaInicial = $request->inicio ?? ''; 
            $fechaFinal = $request->fin ?? ''; 
            if ($fechaInicial || $fechaFinal) {
                $horasExtra = HoraExtra::
                wherePersonalId(auth()->user()->personal->id)
                ->whereBetween('inicio', [$fechaInicial . ' 00:00:00', $fechaFinal . ' 23:59:59'])
                ->whereBetween('fin', [$fechaInicial . ' 00:00:00', $fechaFinal . ' 23:59:59'])
                ->where('es_aprobado',true)
                ->orderBy('inicio', 'desc')
                ->paginate(50);
                $tiempos=Asistencia::wherePersonalId(auth()->user()->personal->id)
                                    ->whereBetween('hora_marcada', [$fechaInicial . ' 00:00:00', $fechaFinal . ' 23:59:59'])
                                    ->whereBetween('hora_marcada', [$fechaInicial . ' 00:00:00', $fechaFinal . ' 23:59:59'])
                                    ->where('tipo_asistencia','=','horaExtra')
                                    ->orderBy('hora_marcada', 'desc')
                                    ->paginate(50);
                                            
                }
            else {

                $horasExtra = HoraExtra::where('es_aprobado',true)
                                        ->wherePersonalId(auth()->user()->personal->id)
                                        ->orderBy('inicio', 'desc')->paginate(50);
                $tiempos = Asistencia::wherePersonalId(auth()->user()->personal->id)
                                        ->where('tipo_asistencia', '=', 'horaExtra')
                                        ->orderBy('hora_marcada', 'desc')
                                        ->paginate(50);
            }
            if (!$tiempos->isEmpty()) {
                foreach ($tiempos as $time) {
                    if ($time->hora_extra_manana !== null && $time->hora_extra_manana !== 0) {
                        $Extrahour[] = $time->hora_extra_manana;
                    } elseif ($time->hora_extra_tarde !== null && $time->hora_extra_tarde !== 0) {
                        $Extrahour[] = $time->hora_extra_tarde;
                    }
                }
            } else {
                $Extrahour = 0;
            }
            
            
            return view('rrhh.permisos.horas_extra.mis_horas_extras',compact('horasExtra','Extrahour','tiempos'));
        }
        else {return redirect(route('home'));}    
    }
    
    public function miHoraExtraSolicitud()
    {   
        if (26 === auth()->user()->personal->id) {
            return view('rrhh.permisos.horas_extra.crear_mi_hora');
        }
        else{return redirect(route('home'));}
    }
}
