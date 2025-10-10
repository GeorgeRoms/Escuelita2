<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

trait HandlesDbErrors
{
    /**
     * Ejecuta un callback con try–catch y redirige al error.general si algo falla.
     *
     * @param  callable  $callback  Código a ejecutar
     * @param  string|null $successRoute  route() a donde redirigir si todo sale bien
     * @param  string|null $successMessage Flash message "success"
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|mixed
     */
    protected function safe(callable $callback, ?string $successRoute = null, ?string $successMessage = null)
    {
        try {
            $result = $callback();

            if ($successRoute) {
                return redirect()->route($successRoute)
                    ->with('success', $successMessage ?? 'Operación realizada correctamente.');
            }

            return $result; // Para vistas (index/show/edit) retorna la view
        } catch (\Throwable $e) {
            // Log con contexto útil
            Log::error('DB/APP Error', [
                'exception' => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'user_id'   => auth()->id(),
                'route'     => request()->fullUrl(),
                // 'trace'   => $e->getTraceAsString(), // habilita si necesitas más detalle en logs
            ]);

            return redirect()->route('error.general')
                ->with('mensaje', 'Algo ha fallado, por favor intente de nuevo.');
        }
    }
}
