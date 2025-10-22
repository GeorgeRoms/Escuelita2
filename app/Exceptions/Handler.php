<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\View\ViewException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; // 👈🏻 FALTA ESTE
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (\Illuminate\Database\QueryException $e, $request) {
    if (config('app.debug')) return null; // deja a Laravel mostrar el error
    $folio = \Illuminate\Support\Str::uuid();
    Log::error('QueryException', ['folio'=>(string)$folio, 'exception'=>$e]);
    return $this->failResponse($request, 'Ocurrió un error de base de datos.', $folio);
});

$this->renderable(function (\Illuminate\View\ViewException $e, $request) {
    if (config('app.debug')) return null;
    $folio = \Illuminate\Support\Str::uuid();
    Log::error('ViewException', ['folio'=>(string)$folio, 'exception'=>$e]);
    return $this->failResponse($request, 'Ocurrió un error al renderizar la vista.', $folio);
});
    }

    protected function failResponse($request, string $msg, $folio)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $msg.' Folio: '.(string)$folio,
                'folio'   => (string)$folio,
            ], 500);
        }

        return redirect()->route('error.general')
            ->with('mensaje', $msg.' Folio: '.(string)$folio);
    }

    public function render($request, Throwable $e)
    {
        // 👇 esto primero
    if (config('app.debug')) {
        return parent::render($request, $e);
    }

    if ($e instanceof \Illuminate\Validation\ValidationException ||
        $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException ||
        $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
        return parent::render($request, $e);
    }

    $folio = \Illuminate\Support\Str::uuid();
    Log::error('Excepción no controlada', ['folio'=>(string)$folio, 'exception'=>$e]);
    return $this->failResponse($request, 'Algo ha fallado, por favor intenta de nuevo.', $folio);
    }
}

