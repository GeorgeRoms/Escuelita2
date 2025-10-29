<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno; // Asegúrate de que este sea el modelo correcto (aunque usamos DB::table)
use App\Models\Kardex; 
use App\Models\Profesore; 
use Illuminate\Support\Facades\DB;
use App\Support\Safe; // Manteniendo tu estructura Safe::run
use Barryvdh\DomPDF\Facade\Pdf; // Importación para el PDF

class ReporteAlumnoController extends Controller
{
    /**
     * Muestra el historial académico de un alumno.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function ver(Request $request)
    {
        // 1. Obtener el número de control (usamos query() ya que es una petición GET)
        $noControl = $request->query('no_control');
        
        // 2. VALIDACIÓN: Asegurar que el campo no esté vacío (aunque el frontend debe evitarlo)
        if (empty($noControl)) {
            return redirect()->route('reportes.index')
                ->with('error', 'ERROR: Debe proporcionar un número de control.');
        }

        // 3. VERIFICAR EXISTENCIA DEL ALUMNO
        $alumnoInfo = DB::table('alumnos')
            ->select('no_control', DB::raw("TRIM(CONCAT(nombre,' ',apellido_pat,' ',COALESCE(apellido_mat,''))) AS nombre_completo"))
            ->where('no_control', $noControl)
            ->first();

        if (!$alumnoInfo) {
            // SI EL ALUMNO NO EXISTE: Redirige a la página de reportes con el mensaje de error
            return redirect()->route('reportes.index')
                ->with('error', "ERROR: El número de control {$noControl} no existe.");
        }

        // 4. Si el alumno existe, procedemos a llamar al SP
        try {
            // Llamamos al SP
            $historial = DB::select('CALL historial_alumno(?)', [$noControl]);
        } catch (\Exception $e) {
            \Log::error("Error al llamar al SP historial_alumno({$noControl}): " . $e->getMessage());
             return redirect()->route('reportes.index')
                ->with('error', "ERROR: Error en la consulta de historial para el No. Control {$noControl}.");
        }
        
        // 5. Mostrar la vista
        return view('reportes.alumno-ver', compact('alumnoInfo', 'historial'));
    }

    /**
     * Genera el PDF del historial académico de un alumno.
     * (Se mantiene sin cambios de lógica, pero se debería considerar agregar la misma verificación de existencia).
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function historialPdf(Request $request)
    {
        $noControl = $request->query('no_control');

        // *************** VERIFICACIÓN DE EXISTENCIA PARA EL PDF (AGREGADA) *****************
        $alumnoInfoCheck = DB::table('alumnos')->where('no_control', $noControl)->first();
        if (!$alumnoInfoCheck) {
            return redirect()->route('reportes.index')
                ->with('error', "ERROR: No se puede generar PDF, el número de control {$noControl} no existe.");
        }
        // ***********************************************************************************
        
        // Llama al SP actualizado (incluye a.semestre e i.promedio)
        $stmt = DB::getPdo()->prepare('CALL historial_alumno(?)');
        $stmt->execute([$noControl]);
        $historial = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $stmt->closeCursor();

        // Encabezado
        $alumnoInfo = (object)[
            'no_control'      => $noControl,
            'nombre_completo' => optional(collect($historial)->first())->alumno ?? '',
        ];

        // Logo base64 (MIME correcto)
        $logoB64 = null;
        $logoPath = public_path('images/escuelita-logo.png');
        if (is_file($logoPath)) {
            $mime = mime_content_type($logoPath) ?: 'image/png';
            $logoB64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($logoPath));
        }

        $data = [
            'alumnoInfo' => $alumnoInfo,
            'historial'  => $historial,
            'fecha'      => now()->format('d/m/Y H:i'),
            'logoB64'          => $logoB64,
        ];

        $pdf = Pdf::loadView('reportes.historial_pdf', $data)
                  ->setPaper('a4', 'landscape'); // horizontal por ancho de columnas

        return $pdf->download("historial_{$noControl}.pdf");
        // o: return $pdf->stream(...);
    }

    
}
