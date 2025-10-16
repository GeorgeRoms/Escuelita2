<?php
namespace App\Support;

class Responder
{
    public static function ok($request, $route, $message, $data = null, $status = 200)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => $message, 'data' => $data], $status);
        }
        return redirect()->route($route)->with('success', $message);
    }

    public static function fail($request, $folio, $route, $message = null, $status = 500)
    {
        $msg = ($message ?: 'Algo ha fallado, por favor intenta de nuevo.') . ' Folio: '.$folio;
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => $msg, 'folio' => (string)$folio], $status);
        }
        return redirect()->route($route)->with('mensaje', $msg);
    }
}
