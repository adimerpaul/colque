<?php

namespace App\Http\Controllers\Activo;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateActivoFijoRequest;
use App\Http\Requests\UpdateActivoFijoRequest;
use App\Models\ContratoPlantilla;
use App\Patrones\ActaActivo;
use App\Patrones\Contrato;
use App\Patrones\Fachada;
use Illuminate\Support\Facades\Redirect;
use App\Models\Activo\ActivoFijo;
use App\Models\Activo\DetalleActivo;
use App\Models\Activo\Tipo;
use Flash;
use DB;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use function redirect;
use function view;

class ActivoFijoController extends Controller
{
    public function index(Request $request)
    {
        $txtBuscar = $request->txtBuscar;
        if (is_null($txtBuscar))
            $txtBuscar = '';

        $txtEstado = $request->txtEstado;
        if (is_null($txtEstado))
            $txtEstado = '';

        $personalId = $request->personal_id;
        if (is_null($personalId))
            $personalId = '';

        $tipoId = $request->tipo_id;
        if (is_null($tipoId))
            $tipoId = '';

        $activosFijos = ActivoFijo::
        where(function ($q) use ($txtBuscar) {
            $q->where('codigo', 'ilike', '%' . $txtBuscar . '%')
                ->orWhere('descripcion', 'ilike', '%' . $txtBuscar . '%');
        })
            ->where('estado', 'ilike', '%' . $txtEstado . '%')
            ->where(function ($q) use ($personalId) {
                if ($personalId !== '' and $personalId !== '%') {
                    $q->where('personal_id', $personalId);
                }
            })
            ->where(function ($q) use ($tipoId) {
                if ($tipoId !== '' and $tipoId !== '%') {
                    $q->where('tipo_id', $tipoId);
                }
            })
            ->orderBy('codigo')
            ->paginate(100);
        return view('activos.activos_fijos.index', compact('activosFijos'));
    }


    public function create()
    {
        return view('activos.activos_fijos.create');
    }


    public function store(CreateActivoFijoRequest $request)
    {
        \DB::beginTransaction();
        try {
            $input = $request->all();
            $input["codigo"] = "CMC-" . $request->codigo;

            if (ActivoFijo::where('codigo', $input["codigo"])->exists()) {
                Flash::error('Ya existe un codigo en otro registro');

                return redirect()
                    ->route('activos-fijos.create');
            }
            $tipo = Tipo::find($request->tipo_id);
            $numero = $tipo->numero;

            if (strlen($request->codigo) != strlen($numero)
                or substr($request->codigo, 0, 1) != substr($numero, 0, 1)) {
                Flash::error('Formato incorrecto del código para el tipo de activo seleccionado');
                return redirect()->route('activos-fijos.create');
            }


            $activo = ActivoFijo::create($input);
            $input["activo_fijo_id"] = $activo->id;
            DetalleActivo::create($input);
            \DB::commit();

            Flash::success('Activo guardado correctamente.');

            echo "<script>
                    window.location.href = '/activos-fijos';
                    window.open('/imprimir-activo-fijo/'+'$activo->id', '_blank');
            </script>";

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->make_exception($e);
        }
    }


    public function edit($id)
    {
        $activofijo = ActivoFijo::find($id);
        return view('activos.activos_fijos.edit', compact('activofijo'));
    }

    public function generarActa($id, $inicio, $fin)
    {
        $activos = ActivoFijo::wherePersonalId($id)
            ->whereBetween('fecha_adquisicion', [$inicio, $fin])
            ->orderBy('tipo_id')->orderBy('codigo')
            ->get();
        if ($activos->count() == 0) {
            Flash::error('No existen activos');
            return redirect()->route('activos-fijos.index');
        }
        $contrato = $this->reemplazarDocumento($activos);
        $firmas = $this->reemplazarFirma($activos);
        $vistaurl = "activos.activos_fijos.acta";
        $view = \View::make($vistaurl, compact('contrato', 'activos', 'firmas'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper('a4', 'portrait');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(520, 810, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 7, array(0, 0, 0));
        return $pdf->stream('ActaEntrega' . $activos[0]->personal->ci . '.pdf');
    }

    public function reemplazarDocumento($activos)
    {
        $contrato = ContratoPlantilla::whereTipo('Activos')->first();
        $contrato = $contrato->descripcion;


        $contrato = str_replace(ActaActivo::Trabajador, $activos[0]->personal->nombre_completo, $contrato);
        $contrato = str_replace(ActaActivo::Carnet, $activos[0]->personal->ci, $contrato);
        $contrato = str_replace(ActaActivo::Cargo, $activos[0]->personal->cargo, $contrato);
        $contrato = str_replace(Contrato::Fecha, date('d/m/Y'), $contrato);

        return $contrato;
    }

    public function reemplazarFirma($activos)
    {
        $firmas = ContratoPlantilla::whereTipo('Firmas')->first();
        $firmas = $firmas->descripcion;


        $firmas = str_replace(ActaActivo::Trabajador, $activos[0]->personal->nombre_completo, $firmas);
        $firmas = str_replace(ActaActivo::Carnet, $activos[0]->personal->ci, $firmas);

        return $firmas;
    }


    public function update(UpdateActivoFijoRequest $request, $id)
    {
        $activosfijo = ActivoFijo::find($id);
        $input = $request->all();
        $input["codigo"] = 'CMC-' . $request->codigo_numero;

        $codigo = ActivoFijo::whereCodigo($input["codigo"])->where('id', '<>', $activosfijo->id)->count();

        if ($codigo > 0) {
            Flash::error('Ya existe un codigo con otro registro');

            return redirect()
                ->route('activos-fijos.edit', ['activos_fijo' => $activosfijo->id]);
        }
        $activosfijo->update($input);
        $activosfijo->save();
        Flash::success('Activo modificado correctamente.');

        return redirect(route('activos-fijos.index'));

    }


    public function getProximoCodigo($id)
    {
        $activo = ActivoFijo::whereTipoId($id)->orderByDesc('codigo')->first();

        if ($activo) {
            $numero = $activo->codigo_numero;
        } else {
            $tipo = Tipo::find($id);
            $numero = $tipo->numero;
        }

        $numero = $numero + 1;
        return $numero;
    }

    public function imprimir($id)
    {
        $activo = ActivoFijo::find($id);
        if (empty($activo)) {
            return response()->json(['msg' => 'Activo fijo no encontrado']);
        }
        //generador qr
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($this->generarQr($activo)));

        $vistaurl = "activos.activos_fijos.imprimir";
        $view = \View::make($vistaurl, compact('activo', 'qrcode'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        $pdf->setPaper(array(0, 0, 141.73, 83.87));
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        return $pdf->stream('Activo' . $activo->codigo . '.pdf');

    }

    private function generarQr($activo)
    {
        $urlQR =
            $activo->codigo .
            "\n- NOMBRE: " . $activo->descripcion .
            "\n- CANTIDAD: " . $activo->cantidad_unidad;
        return $urlQR;
    }
}
